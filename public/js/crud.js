"use strict";

$(document).ready(() => {
    let
        href = window.location.href,
        newHref,
        sortObj = {
            sorted: false,
            href: null
        },
        searchFlag = false,
        searchObj = {
            searched: false,
            href: null
        },
        changeDepartmentFlag = false,
        page = 2,
        lastPage = 50,
        oldLastPage,
        timer;

    $('.employees').on('scroll', loadNewEmployeesItems);

    $('#sort').on('change', sortEmployees);

    $('#department').on('change', changeDepartment);

    $('input[type=search]').on('input', search);

    $('body').on('click', '.employees__item', function () {
        console.log(this);
    });


    function loadNewEmployeesItems () {
        if ((this.scrollHeight - $(this).height()) === $(this).scrollTop()) {

            setNewLasPage();

            if(page > lastPage) return;

            if(changeDepartmentFlag) {
                // let newHref = href;
                let href = newHref + `?page=${page}`;
            }

            if(sortObj.sorted) {
                href = sortObj.href + `&page=${page}`;
            }

            if(searchObj.searched) {
                href = searchObj.href + `&page=${page}`;
            }

            ajaxGet(href, (result) => {
                $('.employees').append(result);
            });

            page++;
        }
    }

    function setNewLasPage () {
        let lp = $('#lastPage');
        lastPage = lp.val();
        lp.remove();
    }

    function sortEmployees () {
        let order = $('.order');
        let orderBy = order.find('input:checked').val();
        let field = this.value;

        let newSortObj = {
            href: order.attr('data-url') + `?field=${field}&orderBy=${orderBy}`,
            sorted: true
        };
        
        Object.assign(sortObj, newSortObj);



        $('.employees').scrollTop(0);

        loadDepartmentAjax(sortObj.href);

        page = 2;
    }

    function changeDepartment () {
        page = 2;
        href = this.value;
        changeDepartmentFlag = true;
        searchObj.searched = false;
        $('.employees').scrollTop(0);
        loadDepartmentAjax(href);
        newHref = href;
    }

    function loadDepartmentAjax (href) {
        ajaxGet(href, (result) => {
            $('.employees__item').remove();
            $('.employees').append(result);
            setNewLasPage();
        });
    }

    function search () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if($(this).val().length > 2) {
                page = 2;
                $('.employees__onload').addClass('employees__onload_active');
                searchFlag = true;
                let url = $(this).closest('form').attr('action');
                let field = $('select[name=field]').val();
                let value = $(this).val();

                let newSearchObj = {
                    searched: true,
                    href: url + `?field=${field}&value=${value}`
                };
                Object.assign(searchObj, newSearchObj);

                ajaxPost(url, {field, value}, (result) => searchAjaxSuccess(result));

            } else if (searchFlag) showOldEmployeesItems();
        },300);
    }

    function showOldEmployeesItems () {
        $('.employees__item:visible').remove();
        setTimeout(() => {
            $('.employees__item').css({'display':'flex'});
        },100);
        searchFlag = false;
        searchObj.searched = false;
        lastPage = oldLastPage;
        page = 2;
    }

    function searchAjaxSuccess (result) {
        $('.employees__item').css({'display':'none'});
        $('.employees').append(result);
        $('.employees__onload').removeClass('employees__onload_active');
        oldLastPage = lastPage;
        setNewLasPage();
    }



});