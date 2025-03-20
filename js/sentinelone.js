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

// Formatters
var format_sentinelone_active_threats = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-success">'+i18n.t('no')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-danger">'+i18n.t('yes')+'</span>' : colvar)
    col.html(colvar)
}

var format_sentinelone_agent_running = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-danger">'+i18n.t('no')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-success">'+i18n.t('yes')+'</span>' : colvar)
    col.html(colvar)
}

var format_sentinelone_enforcing_security = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-danger">'+i18n.t('no')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-success">'+i18n.t('yes')+'</span>' : colvar)
    col.html(colvar)
}

var format_sentinelone_self_protection = function(colNumber, row){
    var col = $('td:eq('+colNumber+')', row),
        colvar = col.text();
    colvar = colvar == '0' ? '<span class="label label-danger">'+i18n.t('no')+'</span>' :
    colvar = (colvar == '1' ? '<span class="label label-success">'+i18n.t('yes')+'</span>' : colvar)
    col.html(colvar)
}