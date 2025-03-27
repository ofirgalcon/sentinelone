#!/usr/local/munkireport/munkireport-python3

'''Gathers information from SentintelOne sentinelctl binary. Calls once for
each sub-category ("filter") to avoid a ton of processing overhead - there
is no structured data format available here anymore.'''

import subprocess
import sys
import os
from datetime import datetime
from typing import Dict, Optional, List, Tuple

sys.path.insert(0, '/usr/local/munki')
sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist
#pylint: disable=C0103
#pylint: disable=C0301

# Constants
SENTINEL_BINARY = '/Library/Sentinel/sentinel-agent.bundle/Contents/MacOS/sentinelctl'
CACHE_SUBPATH = 'cache'
OUTPUT_FILENAME = 'sentinelone.plist'

def parse_status_output(output: str) -> Dict[str, str]:
    """Parse the status output into a dictionary.
    
    Args:
        output: Raw output string from sentinelctl
        
    Returns:
        Dictionary of key-value pairs
    """
    result = {}
    for line in output.split('\n'):
        if not line.strip():
            continue
        try:
            key, value = line.split(':', 1)
            result[key.strip()] = value.strip()
        except ValueError:
            # Skip lines that don't contain a colon
            continue
    return result

def get_status_data(s1_filter: str) -> Optional[Dict[str, str]]:
    '''Runs the status command with the specified filter string
    
    Args:
        s1_filter: Filter string to pass to sentinelctl
        
    Returns:
        Dictionary of status data or None if there was an error
    '''
    if not os.path.isfile(SENTINEL_BINARY):
        print("sentinelctl binary is missing - exiting")
        return None

    cmd = [SENTINEL_BINARY, 'status', '--filters', s1_filter]
    try:
        sp = subprocess.Popen(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        out, err = sp.communicate()
        
        if sp.returncode != 0:
            print(f"Error trying to execute status with filter {s1_filter}: {err}")
            return None
            
        # Strip out the line containing the filter name
        output_lines = out.decode('UTF-8').splitlines(True)[1:]
        output = ''.join(output_lines)
        
        # Parse the output into a dictionary
        result = parse_status_output(output)
        return result
        
    except subprocess.SubprocessError as e:
        print(f"Failed to execute sentinelctl: {e}")
        return None
    except Exception as e:
        print(f"Unexpected error processing sentinelctl output: {e}")
        return None

def parse_last_seen(timestamp: str) -> str:
    '''Convert Last Seen timestamp to Unix timestamp
    
    Args:
        timestamp: Timestamp string from sentinelctl
        
    Returns:
        Unix timestamp as string
    '''
    try:
        # Try different date formats
        formats = [
            '%m/%d/%y, %I:%M:%S %p',  # Format from SentinelOne output (e.g. "3/18/25, 5:25:02 PM")
            '%Y-%m-%d %H:%M:%S',
            '%Y-%m-%d %H:%M:%S %Z',
            '%Y-%m-%d %H:%M:%S.%f',
            '%Y-%m-%d %H:%M:%S.%f %Z'
        ]
        
        for fmt in formats:
            try:
                # Clean up the timestamp string
                timestamp = timestamp.strip()
                dt = datetime.strptime(timestamp, fmt)
                unix_time = str(int(dt.timestamp()))
                return unix_time
            except ValueError:
                continue
                
        return ''
        
    except Exception as e:
        print(f"Error parsing timestamp: {e}")
        return ''

def main():
    """Main"""
    agent_data = get_status_data("Agent")
    mgmt_data = get_status_data("Management")
    
    if not agent_data or not mgmt_data:
        sys.exit(1)

    # Build results dict that is compatible with the existing model
    result = {
        # Legacy boolean fields (convert to 0/1)
        'active-threats-present': "1" if agent_data.get('Infected', '').lower() == 'yes' else "0",
        'agent-running': "1" if agent_data.get('Ready', '').lower() == 'yes' else "0",
        'enforcing-security': "1" if agent_data.get('ES Framework', '').lower() == 'started' else "0",
        'self-protection-enabled': "1" if agent_data.get('Protection', '').lower() == 'enabled' else "0",
        
        # Text fields (keep original values)
        'agent-version': agent_data.get('Version', ''),
        'agent-id': agent_data.get('ID', ''),
        'mgmt-url': mgmt_data.get('Server', ''),
        'agent-operational-state': agent_data.get('Agent Operational State', ''),
        'remote-profiler': agent_data.get('Remote Profiler', ''),
        'network-monitoring': agent_data.get('Agent Network Monitoring', ''),
        'network-extension': agent_data.get('Network Extension', ''),
        'content-filter': agent_data.get('Network Extension Content Filter', ''),
        'network-quarantine': agent_data.get('Network Quarantine', ''),
        'compatible-os': agent_data.get('Compatible OS', ''),
        'site-key': mgmt_data.get('Site Key', ''),
        'connected': mgmt_data.get('Connected', '')
    }
    
    # Process Last Seen timestamp if available
    if 'Last Seen' in mgmt_data:
        result['last-seen'] = parse_last_seen(mgmt_data['Last Seen'])
        
    # Process Agent Install Time if available
    if 'Install Date' in agent_data:
        result['agent-install-time'] = parse_last_seen(agent_data['Install Date'])

    # Write results of checks to cache file
    cachedir = os.path.join(os.path.dirname(os.path.realpath(__file__)), CACHE_SUBPATH)
    output_plist = os.path.join(cachedir, OUTPUT_FILENAME)
    
    try:
        FoundationPlist.writePlist(result, output_plist)
    except Exception as e:
        print(f"Failed to write plist file: {e}")
        sys.exit(1)

if __name__ == "__main__":
    main()
