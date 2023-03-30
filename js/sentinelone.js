// Filters

var agent_running = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^running$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^not_running$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '!= 1';
        // Clear global search
        d.search.value = '';
    }
}

var active_threats = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^threats_present$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^threats_not_present$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '!= 1';
        // Clear global search
        d.search.value = '';
    }
}

var self_protection_enabled = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^self_protected$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^not_self_protected$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '!= 1';
        // Clear global search
        d.search.value = '';
    }
}

var enforcing_security = function(colNumber, d){
    // Look for 'Enabled' keyword
    if(d.search.value.match(/^enforced$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '= 1';
        // Clear global search
        d.search.value = '';
    }

    // Look for 'Disabled' keyword
    if(d.search.value.match(/^not_enforced$/))
    {
        // Add column specific search
        d.columns[colNumber].search.value = '!= 1';
        // Clear global search
        d.search.value = '';
    }
}