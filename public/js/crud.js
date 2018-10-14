"use strict";

$(document).ready(() => {
    let
        href = window.location.href,
        sortObj = {
            sorted: false,
            href: null,
        },
        searchFlag = false,
        page = 2,
        lastPage = 50,
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

            if(page > lastPage) return;

            if(sortObj.sorted) {
                href = sortObj.href + `&page=${page}`;
            }

            ajaxGet(href, (result) => {
                $('.employees').append(result);
            });

            page++;
        }
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
        loadDepartment(sortObj.href);

    }

    function changeDepartment () {
        href = this.value;
        loadDepartment(href);
    }

    function loadDepartment (href) {
        ajaxGet(href, (result) => {
            $('.employees__item').remove();
            $('.employees').append(result);
        });
    }

    function search () {
        clearTimeout(timer);
        timer = setTimeout(() => {
            if($(this).val().length > 2) {
                $('.employees__onload').addClass('employees__onload_active');
                searchFlag = true;
                let url = $(this).closest('form').attr('action');
                let field = $('select[name=field]').val();
                let value = $(this).val();

                ajaxPost(url, {field, value}, (result) => {
                    $('.employees__item').css({'display':'none'});
                    $('.employees').append(result);
                    $('.employees__onload').removeClass('employees__onload_active');
                })
            } else {
                if(searchFlag) {
                    $('.employees__item:visible').remove();
                    setTimeout(() => {
                        $('.employees__item').css({'display':'flex'});
                    },100);
                    searchFlag = false;
                }
            }
        },300);
    }



});