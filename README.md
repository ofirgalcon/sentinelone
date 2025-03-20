SentinelOne module
================

Reports information about the SentinelOne security agent and its status. Displays the status of the SentinelOne client including active threats, agent status, and security features.

Features:
* Provides detailed statistics about active threats
* Tracks agent running status across the fleet
* Monitors self-protection status
* Reports on security policy enforcement
* Tracks agent versions and their distribution
* Monitors management console connectivity
* Provides network security status information


Table Schema
---

* id - Unique ID
* serial_number - varchar(255) - machine's serial number
* active_threats_present - boolean - Indicates if there are active threats detected
* agent_id - varchar(255) - Unique identifier for the SentinelOne agent
* agent_running - boolean - Indicates if the SentinelOne agent is currently running
* agent_version - varchar(255) - Version of the installed SentinelOne agent
* enforcing_security - boolean - Indicates if security policies are being enforced
* last_seen - varchar(255) - Timestamp of when the agent was last seen
* mgmt_url - varchar(255) - URL of the SentinelOne management console
* self_protection_enabled - boolean - Indicates if self-protection features are enabled
* agent_install_time - varchar(255) - Timestamp of when the agent was installed
* agent_operational_state - boolean - Indicates if the agent is in operational state
* remote_profiler - boolean - Indicates if remote profiling is enabled
* network_monitoring - boolean - Indicates if network monitoring is enabled
* network_extension - boolean - Indicates if network extension is enabled
* content_filter - boolean - Indicates if content filtering is enabled
* network_quarantine - boolean - Indicates if network quarantine is enabled
* compatible_os - boolean - Indicates if the OS is compatible
* site_key - varchar(255) - Site key for SentinelOne management
* connected - boolean - Indicates if the agent is connected to the management console

