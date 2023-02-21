
#!/usr/local/munkireport/munkireport-python3

'''Gathers information from SentintelOne sentinelctl binary. Calls once for
each sub-category ("filter") to avoid a ton of processing overhead - there
is no structured data format available here anymore.'''

import subprocess
import sys
import os
# import dateutil.parser as dp # Not included in munki-python

sys.path.insert(0, '/usr/local/munki')
sys.path.insert(0, '/usr/local/munkireport')

from munkilib import FoundationPlist
#pylint: disable=C0103
#pylint: disable=C0301

def get_status_data(s1_filter):
    '''Runs the status command with the specified filter string'''
    s1_binary = '/Library/Sentinel/sentinel-agent.bundle/Contents/MacOS/sentinelctl'

    if os.path.isfile(s1_binary):
        cmd = [s1_binary, 'status', '--filters', s1_filter]
        sp = subprocess.Popen(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
        out, err = sp.communicate()
        if sp.returncode != 0:
            print(("Error trying to execute status with filter %s: %s" % (s1_filter, err)))
            sys.exit(1)
        else:
            # Strip out the line containing the filter name, then map to dict. Skip last line
            # as sentinelctl currently puts a blank newline at the end which breaks the map.
            out = str(''.join(out.decode('UTF-8').splitlines(True)[1:]))
            return dict([list(map(str.strip, s.split(':', 1))) for s in out.split('\n')[:-1]])
    else:
        print("sentinelctl binary is missing - exiting")
        sys.exit(1)


def main():
    """Main"""

    agent_data = get_status_data("Agent")
    mgmt_data = get_status_data("Management")

    # Build results dict that is compatible with the existing model
    result = {}
    # Translate bool values
    if "yes" in agent_data['Infected']:
        result.update({'active-threats-present': "1"})
    else:
        result.update({'active-threats-present': "0"})
    if "yes" in agent_data['Ready']:
        result.update({'agent-running': "1"})
    else:
        result.update({'agent-running': "0"})
    if "started" in agent_data['ES Framework']:
        result.update({'enforcing-security': "1"})
    else:
        result.update({'enforcing-security': "0"})
    if "enabled" in agent_data['Protection']:
        result.update({'self-protection-enabled': "1"})
    else:
        result.update({'self-protection-enabled': "0"})

    # Rest of values can be sent relatively cleanly
    result.update({'agent-version': agent_data['Version']})
    result.update({'agent-id': agent_data['ID']})
    # result.update({'last-seen': dp.parse(mgmt_data['Last Seen']).strftime('%s')}) # dateutil not in munki-python
    result.update({'mgmt-url': mgmt_data['Server']})

    # Write results of checks to cache file
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))

    output_plist = os.path.join(cachedir, 'sentinelone.plist')
    FoundationPlist.writePlist(result, output_plist)

if __name__ == "__main__":
    main()
