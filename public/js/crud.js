"use strict";

$(document).ready(() => {

    let department = $('#department'),
        sortUrl = $('.order').attr('data-url'),
        page = 2,
        timer,
        newEmployees = null;

    addEmployeesScrollEvent($('.employees'));

    $('#sort').on('change', sortEmployees);
    
    $('body').on('change', '.search__form', function () {
       console.log(this)
    });

    department.on('change', changeDepartment);

    addInputChangeEvent($('.search__form').find('input[name=value]'));

    $('body').on('click', '.employees__item', function () {
        console.log(this);
    });


    function changeDepartment () {
        let url = this.value;

        ajaxGet(url, (content) => {
            appendNewEmployee(content);
        })
    }

    function sortEmployees () {
        let hash = getHashFromUrl(department.val());
        let url = `${sortUrl}/${hash}`;

        let order = $('.order').find('input:checked').val();

        ajaxPost(url, {sort: this.value, orderBy: order}, (result) => {
            appendNewEmployee(result);
        });
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
                let hash = getHashFromUrl(department.val());
                let attr = $(this).attr('current_page');
                let maxPage = $(this).attr('last_page');
                if(page > maxPage) return;
                let newAttr = attr + `/${hash}?page=${page}`;
                ajaxGet(newAttr, (result) => {
                    $(this).append(result);
                });
                page++;
            }
        })
    }

    function addInputChangeEvent (input) {
        $(input).on('keyup', function () {
            clearTimeout(timer);
            timer = setTimeout(() => {
                if($(this).val().length > 2) {
                    let url = $(this).closest('form').attr('action');
                    let field = $('select[name=field]').val();
                    let value = $(this).val();

                    ajaxPost(url, {field, value}, (result) => {
                        console.log(result);
                        $('.employees__item').remove();
                        $('employees').append(result);
                    })
                }
            },300);
        })
    }




});