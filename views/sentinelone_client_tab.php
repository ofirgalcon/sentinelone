<div id="lister" style="font-size: large; float: right;">
    <a href="/show/listing/sentinelone/sentinelone" title="List">
        <i class="btn btn-default tab-btn fa fa-list"></i>
    </a>
</div>
<div id="report_btn" style="font-size: large; float: right;">
    <a href="/show/report/sentinelone/sentinelone" title="Report">
        <i class="btn btn-default tab-btn fa fa-th"></i>
    </a>
</div>
<h2><i class="fa fa-shield"></i> <span data-i18n="sentinelone.client_tab"></span></h2>

<div id="sentinelone-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<div id="sentinelone-view" class="row hide">
    <div class="col-md-6">
        <table class="table table-striped">
            <tr>
                <th data-i18n="sentinelone.threats_present"></th>
                <td id="sentinelone-active_threats_present"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.agent_id"></th>
                <td id="sentinelone-agent_id"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.agent_install_time"></th>
                <td id="sentinelone-agent_install_time"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.agent_running"></th>
                <td id="sentinelone-agent_running"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.agent_version"></th>
                <td id="sentinelone-agent_version"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.enforcing_security"></th>
                <td id="sentinelone-enforcing_security"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.last_seen"></th>
                <td id="sentinelone-last_seen"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.mgmt_url"></th>
                <td id="sentinelone-mgmt_url"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.self_protection_enabled"></th>
                <td id="sentinelone-self_protection_enabled"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.agent_operational_state"></th>
                <td id="sentinelone-agent_operational_state"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.remote_profiler"></th>
                <td id="sentinelone-remote_profiler"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.network_monitoring"></th>
                <td id="sentinelone-network_monitoring"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.network_extension"></th>
                <td id="sentinelone-network_extension"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.content_filter"></th>
                <td id="sentinelone-content_filter"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.network_quarantine"></th>
                <td id="sentinelone-network_quarantine"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.compatible_os"></th>
                <td id="sentinelone-compatible_os"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.site_key"></th>
                <td id="sentinelone-site_key"></td>
            </tr>
            <tr>
                <th data-i18n="sentinelone.connected"></th>
                <td id="sentinelone-connected"></td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
    </div>
</div>

<script>
$(document).on('appReady', function(e, lang) {

    // Get sentinelone data
    $.getJSON( appUrl + '/module/sentinelone/get_data/' + serialNumber, function( data ) {

        // Only show if we have data
        if (data && data.id !== ""){
            // Hide
            $('#sentinelone-msg').text('');
            $('#sentinelone-view').removeClass('hide');

            // Add strings for non-boolean fields
            $('#sentinelone-agent_id').text(data.agent_id);
            $('#sentinelone-agent_version').text(data.agent_version);
            $('#sentinelone-mgmt_url').text(data.mgmt_url);
            $('#sentinelone-site_key').text(data.site_key);

            if(data.last_seen) {
                    // Format date
                    var last_seen = parseInt(data.last_seen);
                    var date = new Date(last_seen * 1000);
                    $('#sentinelone-last_seen').text(date);
            }

            if(data.agent_install_time) {
                    // Format date
                    var install_time = parseInt(data.agent_install_time);
                    var date = new Date(install_time * 1000);
                    $('#sentinelone-agent_install_time').text(date);
            }

            // Helper function to set status labels with context
            function setStatusLabel(elementId, value, isPositive) {
                var labelClass = '';
                var labelText = value;
                
                // Handle legacy boolean fields (0/1)
                if (value === "0" || value === "1") {
                    labelText = value === "1" ? "True" : "False";
                    labelClass = (value === "1") === isPositive ? 'label-success' : 'label-danger';
                }
                // Handle new text fields
                else if (value.toLowerCase() === 'yes' || value.toLowerCase() === 'enabled' || 
                    value.toLowerCase() === 'started' || value.toLowerCase() === 'running' || 
                    value.toLowerCase() === 'active' || value.toLowerCase() === 'compatible') {
                    labelClass = isPositive ? 'label-success' : 'label-danger';
                    // Capitalize first letter of each word
                    labelText = value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
                } else if (value.toLowerCase() === 'no' || value.toLowerCase() === 'disabled' || 
                         value.toLowerCase() === 'not running' || value.toLowerCase() === 'not started') {
                    labelClass = isPositive ? 'label-danger' : 'label-success';
                    // Capitalize first letter of each word
                    labelText = value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase()).join(' ');
                }
                
                if (labelClass) {
                    $('#' + elementId).html('<span class="label ' + labelClass + '">' + labelText + '</span>');
                } else {
                    $('#' + elementId).text(labelText);
                }
            }

            // Set status labels for all fields with their context
            // Legacy boolean fields
            // Threats Present: false is good (no threats)
            setStatusLabel('sentinelone-active_threats_present', data.active_threats_present, false);
            
            // Agent Running: true is good
            setStatusLabel('sentinelone-agent_running', data.agent_running, true);
            
            // Enforcing Security: true is good
            setStatusLabel('sentinelone-enforcing_security', data.enforcing_security, true);
            
            // Self Protection: true is good
            setStatusLabel('sentinelone-self_protection_enabled', data.self_protection_enabled, true);
            
            // Network Quarantine: false is good
            setStatusLabel('sentinelone-network_quarantine', data.network_quarantine, false);
            
            // Connected: true is good
            setStatusLabel('sentinelone-connected', data.connected, true);
            
            // New text fields
            // Agent Operational State: enabled is good
            setStatusLabel('sentinelone-agent_operational_state', data.agent_operational_state, true);
            
            // Remote Profiler: running is good
            setStatusLabel('sentinelone-remote_profiler', data.remote_profiler, true);
            
            // Network Monitoring: started is good
            setStatusLabel('sentinelone-network_monitoring', data.network_monitoring, true);
            
            // Network Extension: running is good
            setStatusLabel('sentinelone-network_extension', data.network_extension, true);
            
            // Content Filter: active is good
            setStatusLabel('sentinelone-content_filter', data.content_filter, true);
            
            // Compatible OS: compatible is good
            setStatusLabel('sentinelone-compatible_os', data.compatible_os, true);
        } else {
            $('#sentinelone-msg').text(i18n.t('no_data'));
        }
    });
});
</script>
