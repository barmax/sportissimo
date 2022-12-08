const SORT_ASC = 'asc';
const SORT_DESC = 'desc';

function getURLSearchParams()
{
    const queryString = window.location.search;
    return new URLSearchParams(queryString);
}

function handlePageSize()
{
    const DEFAULT_PAGE_SIZE = '10';

    const urlParams = getURLSearchParams();
    const pageSize = urlParams.get('pageSize');

    let element = '#pagesize_10';
    if (pageSize !== DEFAULT_PAGE_SIZE) {
        element = '#pagesize_' + pageSize;
    }

    $(element).addClass('active');

    $('.page_size').on('click', function (e) {
        e.defaultPrevented;

        let pageSizeValue = $(this).attr('data-page-size');

        urlParams.set('pageSize', pageSizeValue)
        location.search = urlParams.toString();
    })
}

function handleSortIcon(sort) {
    let iconName = '';
    if (sort === SORT_ASC) {
        iconName = 'arrow_upward';
    } else if (sort === SORT_DESC) {
        iconName = 'arrow_downward';
    }

    $('#sort_order').text(iconName);
}

function handleSort()
{
    const urlParams = getURLSearchParams();
    const sort = urlParams.get('sort');

    handleSortIcon(sort)
    $('#sort-name').on('click', function (e){
        e.defaultPrevented;

        let sortValue = SORT_ASC;
        if (sort === SORT_ASC) {
            sortValue = SORT_DESC;
        }

        urlParams.set('sort', sortValue)
        handleSortIcon(sortValue);
        location.search = urlParams.toString();
    })
}

function handlePage()
{
    const FIRST_PAGE = '1';

    $('.pagination_page').on('click', function (e) {
        e.defaultPrevented;

        const urlParams = getURLSearchParams();
        const page = $(this).attr('data-page');

        let pageValue = page;
        if (page === null || page === FIRST_PAGE) {
            pageValue = FIRST_PAGE;
        }

        urlParams.set('page', pageValue);
        location.search = urlParams.toString();
    })
}

function handleCreateAction() {
    $('#create_brand').click(function (e) {
        e.defaultPrevented;
        const brandName = $('#brand_name');

        $.ajax('/brand/create', {
            type: 'POST',
            data: {
                name: brandName.val()
            },
            success: function (data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    brandName.addClass('invalid');
                    $('#brand_name_description').text(data.message)
                }
            },
            error: function (errorMessage) {
                brandName.addClass('invalid');
                $('#brand_name_description').text(errorMessage)
            }
        })
    });
}

function handleUpdateAction()
{
    $('#update_brand').click(function (e) {
        e.defaultPrevented;
        const brandName = $('#brand_update_name');
        const brandId = $('#brand_update_id');

        $.ajax('/brand/update', {
            type: 'POST',
            data: {
                id: brandId.val(),
                name: brandName.val()
            },
            success: function (data) {
                if (data.status === 'success') {
                    location.reload();
                } else {
                    brandName.addClass('invalid');
                    $('#brand_name_update_description').text(data.message)
                }
            },
            error: function (errorMessage) {
                brandName.addClass('invalid');
                $('#brand_name_description').text(errorMessage)
            }
        })
    });
}

function handleDeleteAction()
{
    $('.delete_action').click(function (e) {
        e.defaultPrevented;
        const id = $(this).attr('delete_id');
        const toDelete = confirm('Delete the brand with ID ' + id + '?');

        if (toDelete === false) {
            return;
        }

        $.ajax('/brand/delete', {
            type: 'POST',
            data: {
                id: id
            },
            success: function () {
                location.reload();
            },
            error: function (errorMessage) {
            }
        })
    });
}

function handleReadAction()
{
    $('.update_action').click(function (e) {
        e.defaultPrevented;
        const id = $(this).attr('update_id');
        $.ajax('/brand/read', {
            type: 'POST',
            data: {
                id: id
            },
            success: function (data) {
                console.log(data);
                $('#brand_update_name').val(data.data.name);
                $('#brand_update_id').val(data.data.id);
            },
            error: function (errorMessage) {
            }
        })
    });
}

$(document).ready(function () {
    $('.modal').modal();

    handlePageSize();
    handleSort();
    handlePage();

    handleCreateAction();
    handleReadAction()
    handleUpdateAction();
    handleDeleteAction()
});