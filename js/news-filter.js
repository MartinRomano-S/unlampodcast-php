$(document).ready(function() {
    
    $('#date-filter').datepicker({
        locale: 'es-es',
        uiLibrary: 'bootstrap4'
    });
    
    reloadPublishedNewsList();
    
    $('.search').off('click').click(() => {
        reloadPublishedNewsList();
    });
});

function reloadPublishedNewsList() {
    
    let data = {};
    let dateFilter = $('#date-filter').val();
    let titleFilter = $('#title-filter').val();
    
    if(dateFilter)
        data.dateFilter = dateFilter;
        
    if(titleFilter)
        data.titleFilter = titleFilter;
    
    $('#publishedNewsList').load('/admin/news/reloadPublicNewsTable.php', data);
}