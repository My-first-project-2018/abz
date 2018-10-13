"use strict";

$(document).ready(() => {

    let department = $('#department'),
        page = 2,
        newEmployees = null;

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