"use strict";

$(document).ready(() => {

    let department = $('#department'),
        sortUrl = $('.order').attr('data-url'),
        href = window.location.href,
        sortObj = {
            sorted: false,
            href: null,
        },
        searchFlag = false,
        page = 2,
        timer,
        newEmployees = null;

    addEmployeesScrollEvent($('.employees'));

    $('#sort').on('change', sortEmployees);
    


    department.on('change', changeDepartment);

    addInputChangeEvent($('.search__form').find('input[name=value]'));

    $('body').on('click', '.employees__item', function () {
        console.log(this);
    });


    function changeDepartment () {
        // let url = this.value;
        href = this.value;

        pagination(href);
    }



    function sortEmployees () {
        // let hash = getHashFromUrl(department.val());
        // let url = `${sortUrl}/${hash}`;
        // console.log(url);
        let order = $('.order').find('input:checked').val();
        let field = this.value;

        let newSortObj = {
            href: $('.order').attr('data-url') + `?field=${field}&orderBy=${order}`,
            sorted: true
        };
        
        Object.assign(sortObj, newSortObj);

        pagination(sortObj.href);






        //
        // ajaxPost(url, {sort: this.value, orderBy: order}, (result) => {
        //     appendNewEmployee(result);
        // });
    }

    function appendNewEmployee (employee) {
        $('.employees').remove();
        $('.crud__content').append(employee);
        setTimeout(() => {
            newEmployees = $('.employees');
            addEmployeesScrollEvent(newEmployees);
        },10);
    }

    function addEmployeesScrollEvent (newEmployees) {
        newEmployees.on('scroll', function () {
            if ((this.scrollHeight - $(this).height()) === $(this).scrollTop()) {

                let url = `${href}?page=${page}`;
                console.log(url);
                let maxPage = $(this).attr('last_page');
                if(page > maxPage) return;

                if(sortObj.sorted) {
                    href = sortObj.href + `&page=${page}`;
                }

                pagination(href);

                page++;
            }
        })
    }

    function pagination (href) {
        ajaxGet(href, (result) => {
            $('.employees__item').remove();
            $('.employees').append(result);

        });
    }

    function addInputChangeEvent (input) {
        $(input).on('input', function () {
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
                        // changeDepartment.call(department);
                        $('.employees__item:visible').remove();
                        setTimeout(() => {
                            $('.employees__item').css({'display':'flex'});
                        },100);
                        searchFlag = false;
                    }
                }
            },300);
        })

    }




});