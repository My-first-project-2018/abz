"use strict";

$(document).ready(() => {
    let
        employees = $('.employees'),
        modal = $('.modal'),
        href = location.href,
        departmentHref = href,
        searchFlag = false,
        changeDepartmentFlag = false,
        page = 2,
        lastPage = 50,
        oldLastPage,
        timer;



    employees.on('scroll', loadNewEmployeesItems);

    $('.addUser').on('click', function (e) {
        showAddUserForm.call(this, e);
    });

    $('.modal__close').on('click', () => {
        modal
            .css({'display':'none'})
            .find('form').remove();
    });

    modal.on('input', 'input[type=search]', function() {
        if($(this).val().length > 2) {

            let url = $(this).attr('data-url').split('/');
            url[url.length - 1] = '';
            url = url.join('/') + getHashFromUrl(departmentHref) + `?value=${$(this).val()}`;
            // console.log(url)
            // console.log(url.join('/') + getHashFromUrl(departmentHref));
            // console.log(departmentHref)
            // console.log($(this).val())

            
            
            ajaxGet(url, function (result) {
                // $('.search__boss').find('p').remove();
                // $('.search__boss').append(result);

                if(result.success){
                    $('.search__boss').find('p').remove();
                    $('.search__boss').append(result);
                } else { //error
                    showAjaxValidateError(result);
                }

            });


            $('.search__boss').addClass('search__boss_active');
        } else {
            $('.search__boss').removeClass('search__boss_active');
        }
    });
    
    modal.on('click', '.search__boss p', function () {
       $('#bossHash').val($(this).attr('data-hash'));
       // $('.search__boss').val($(this).va)
        $('.search__boss').removeClass('search__boss_active');
    });

    modal.on('submit', function (e) {
        e.preventDefault();

    });



    $('#sort').on('change', sortEmployees);

    $('#department').on('change', changeDepartment);

    $('input[type=search]').on('input', search);

    $('body').on('click', '.employees__item', function () {
        console.log(this);
    });

    function showAddUserForm (e) {
        e.preventDefault();
        modal.css({'display':'flex'});
        let url = $(this).attr('href');
        ajaxGet(url, (result) => {
            $('.modal').append(result);
        })
    }

    function loadNewEmployeesItems () {
        if ((this.scrollHeight - $(this).height()) === $(this).scrollTop()) {

            setNewLastPage();

            href = location.href.match(/\?/) ? location.href + `&page=${page}` : location.href + `?page=${page}`;

            ajaxGet(href, (result) => {
                employees.append(result);
            });

            page++;
        }
    }

    function setNewLastPage () {
        let lp = $('#lastPage');
        lastPage = lp.val();
        lp.remove();
    }

    function sortEmployees () {
        clearPagination();

        let order = $('.order');
        let orderBy = order.find('input:checked').val();
        let field = this.value;

        history.pushState('','',departmentHref);
        //form new location.href
        let newStr = order.attr('data-url').split('/');
        newStr[newStr.length-1] = getHashFromUrl(location.href).replace(/\?+/, '');
        newStr = newStr.join('/');

        href = location.href;

        history.pushState('','', newStr + `?field=${field}&orderBy=${orderBy}`);

        employees.scrollTop(0);

        loadDepartmentAjax(location.href);

        page = 2;
    }

    function changeDepartment () {
        clearPagination();
        page = 2;
        history.pushState('','', this.value);
        href =  departmentHref = location.href;
        changeDepartmentFlag = true;
        employees.scrollTop(0);
        loadDepartmentAjax(href);
    }

    function clearPagination () {
        $('.paginationPages').remove();
    }

    function loadDepartmentAjax (href) {
        ajaxGet(href, (result) => {
            $('.employees__item').remove();
            employees.append(result);
            setNewLastPage();
        });
    }

    function search () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if($(this).val().length > 2 && $('.search__form').find('select').val()) {
                clearPagination();
                page = 2;
                $('.employees__onload').addClass('employees__onload_active');
                searchFlag = true;

                let url = $(this).closest('form').attr('action');
                let field = $('select[name=field]').val();
                let value = $(this).val();

                history.pushState('', '', url + `?field=${field}&value=${value}`);

                ajaxGet(location.href, (result) => searchAjaxSuccess(result));

            } else if (searchFlag) showOldEmployeesItems();//if we use search before
        },300);
    }

    function showOldEmployeesItems () {
        $('.employees__item:visible').remove();
        setTimeout(() => {
            $('.employees__item').css({'display':'flex'});
        },100);
        searchFlag = false;
        lastPage = oldLastPage;
        page = 2;
    }

    function searchAjaxSuccess (result) {
        $('.employees__item').css({'display':'none'});
        employees.append(result)
            .find('.employees__onload')
            .removeClass('employees__onload_active');
        // $('.employees__onload').removeClass('employees__onload_active');
        oldLastPage = lastPage;
        setNewLastPage();
    }

    function setDefaultUrl () {

    }


});