"use strict";

$(document).ready(() => {

    let department = $('#department'),
        sortUrl = $('.order').attr('data-url'),
        page = 2,
        newEmployees = null;


    $('#sort').on('change', function () {
        let hash = getHashFromUrl(department.val());
        let sort = `${sortUrl}/${hash}`;

        let order = $('.order').find('input:checked').val();

        console.log(this.value,'__', sort, '__', order)
        
        ajaxPost(sort, {sort: this.value, orderBy: order}, (result) => {
            $('.employees').remove();
            $('.crud__content').append(result);
            setTimeout(() => {
                newEmployees = $('.employees');
                addEmployeesScrollEvent(newEmployees);
            },10);
        });
    });

    addEmployeesScrollEvent($('.employees'));

    department.on('change', changeDepartment);


    $('body').on('click', '.employee__item', function () {

    });


    function changeDepartment () {
        let url = this.value;

        ajaxGet(url, (content) => {
            $('.employees').remove();
            $('.crud__content').append(content);
            setTimeout(() => {
                newEmployees = $('.employees');
                addEmployeesScrollEvent(newEmployees);
            },10);
        })
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




});