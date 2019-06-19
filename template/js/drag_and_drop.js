var DragManager = new function() {

    /*
        elem - элемент, на котором была зажата мышь
        avatar - аватар
        downX/downY - координаты, на которых был mousedown
        shiftX/shiftY - относительный сдвиг курсора от угла элемента
    */

    var dragObject = {};
    var dropObject = {};
    var draggable_elements = {};

    var self = this;

    function onMouseDown(e) {

        if (e.which != 1) return;

        var elem = e.target.closest('.draggable');
        if (!elem) return;

        dragObject.elem = elem;

        // запомним, что элемент нажат на текущих координатах pageX/pageY
        dragObject.downX = e.pageX;
        dragObject.downY = e.pageY;

        result = get_elements_coordinates();
        draggable_elements.elements_coordinates_array = result.elements_coordinates_array;
        draggable_elements.elements_array = result.elements_array;

        return false;
    }

    function get_elements_coordinates(){

        var elements_coordinates_array = [];
        var elements_array = [];

        $(".draggable").each(function(index, element) {

            var position = $(element).position();

            var element_coordinates = {};
            element_coordinates.left = position.left;
            element_coordinates.top = position.top;
            element_coordinates.right = position.left + $(element).width();
            element_coordinates.bottom = position.top + $(element).height();

            elements_coordinates_array[index] = element_coordinates;
            elements_array[index] = element;
        });

        return {
            elements_coordinates_array: elements_coordinates_array,
            elements_array: elements_array
        };
    }

    function onMouseMove(e) {

        if (!dragObject.elem) return; // элемент не зажат

        dragObject.pageX = e.pageX;
        dragObject.pageY = e.pageY;

        if (!dragObject.avatar) { // если перенос не начат...
            var moveX = e.pageX - dragObject.downX;
            var moveY = e.pageY - dragObject.downY;

            // если мышь передвинулась в нажатом состоянии недостаточно далеко
            if (Math.abs(moveX) < 3 && Math.abs(moveY) < 3) {
                return;
            }

            // начинаем перенос
            dragObject.avatar = createAvatar(e); // создать аватар
            if (!dragObject.avatar) { // отмена переноса, нельзя "захватить" за эту часть элемента
                dragObject = {};
                return;
            }

            // аватар создан успешно
            // создать вспомогательные свойства shiftX/shiftY
            var coords = getCoords(dragObject.avatar);
            dragObject.shiftX = dragObject.downX - coords.left;
            dragObject.shiftY = dragObject.downY - coords.top;

            startDrag(e); // отобразить начало переноса
        }

        // отобразить перенос объекта при каждом движении мыши
        dragObject.avatar.style.left = e.pageX - dragObject.shiftX + 'px';
        dragObject.avatar.style.top = e.pageY - dragObject.shiftY + 'px';

        var elem_droppable_index = findDroppable(e);

        if(!(typeof elem_droppable_index === 'undefined') && elem_droppable_index !== dropObject.last_index){

            console.log("индексы не совпадают");

            elem_droppable = $('.droppable')[elem_droppable_index];

            current_sort_number = elem_droppable.dataset.sortNumber;
            last_sort_number = dropObject.last_sort_number;

            var element_outline = document.createElement("div");
            element_outline.className = "element shape droppable";
            element_outline.style.borderColor = "red";
            element_outline.style.borderStyle = "dashed";

            console.log("last_sort_number");
            console.log(last_sort_number);
            console.log("current_sort_number");
            console.log(current_sort_number);

            if(current_sort_number < last_sort_number){
                console.log("%c сделан шаг НАЗАД", "color: green");
                document.getElementById("images_container").insertBefore(element_outline, elem_droppable);

            } else if(current_sort_number > last_sort_number){
                console.log("%c сделан шаг ВПЕРЕД", "color: green");
                document.getElementById("images_container").insertBefore(element_outline, elem_droppable.nextSibling);
            }

            dropObject.last_sort_number = current_sort_number;
            dropObject.last_index = elem_droppable_index;

            if(!dropObject.last_droppable_element){
                dropObject.last_element_outline = element_outline;
            } else {
                document.getElementById("images_container").removeChild(dropObject.last_element_outline);
                dropObject.last_element_outline = element_outline;
            }

            $(".droppable").each(function (index, element) {
                element.dataset.sortNumber = index;
            });

            new_droppable_element = $('.droppable')[elem_droppable_index];
            dropObject.last_droppable_element = new_droppable_element;

        } else if(!(typeof elem_droppable_index === 'undefined') && elem_droppable_index === dropObject.last_index) {
            console.log("индексы совпадают");
        }

        return false;
    }

    function onMouseUp(e) {
        if (dragObject.avatar) { // если перенос идет
            finishDrag(e);
        }

        if (dragObject.elem) {
            dragObject.elem.classList.add('droppable');
            if(dropObject.last_element_outline){
                document.getElementById("images_container").removeChild(dropObject.last_element_outline);
            }
        }

        photo_name_list = '';

        $( ".element" ).each(function() {

            photo_name = this.dataset.photoName;

            if (photo_name_list === "") {
                photo_name_list = photo_name_list + photo_name;
            } else {
                photo_name_list = photo_name_list + ", " + photo_name;
            }

            var image_names = document.getElementById("image_names");
            image_names.value = photo_name_list;

        });

        // перенос либо не начинался, либо завершился
        // в любом случае очистим "состояние переноса" dragObject
        dragObject = {};
        dropObject = {};
    }

    function finishDrag(e) {

        if(dropObject.last_droppable_element){
            elem_droppable =  dropObject.last_droppable_element;
        }

        if(elem_droppable){

            dragObject.elem.removeAttribute('style');

            var new_parameters = {
                parent: elem_droppable.parentNode,
                // nextSibling: elem_droppable.nextSibling,
                nextSibling: elem_droppable,
                position: elem_droppable.position || '',
                left: elem_droppable.left || '',
                top: elem_droppable.top || '',
                zIndex: elem_droppable.zIndex || ''
            };

            if(new_parameters){
                dragObject.elem.apply_new_parameters = function() {
                    new_parameters.parent.insertBefore(dragObject.elem, new_parameters.nextSibling);
                    dragObject.elem.style.position = new_parameters.position;
                    dragObject.elem.style.left = new_parameters.left;
                    dragObject.elem.style.top = new_parameters.top;
                    dragObject.elem.style.zIndex = new_parameters.zIndex
                };
            }
        }

        if (!elem_droppable) {
            self.onDragCancel(dragObject);
        } else {
            dropObject.elem_droppable = elem_droppable;
            self.onDragEnd(dragObject, dropObject);
        }
    }

    function createAvatar(e) {

        // запомнить старые свойства, чтобы вернуться к ним при отмене переноса
        var avatar = dragObject.elem;

        avatar.classList.remove('droppable');

        var old = {
            parent: avatar.parentNode,
            nextSibling: avatar.nextSibling,
            position: avatar.position || '',
            left: avatar.left || '',
            top: avatar.top || '',
            zIndex: avatar.zIndex || ''
        };

        // функция для отмены переноса
        avatar.rollback = function() {
            old.parent.insertBefore(avatar, old.nextSibling);
            avatar.style.position = old.position;
            avatar.style.left = old.left;
            avatar.style.top = old.top;
            avatar.style.zIndex = old.zIndex
        };

        dropObject.old = old;

        var element_outline = document.createElement("div");
        element_outline.className = "element shape droppable";
        element_outline.style.borderColor = "red";
        element_outline.style.borderStyle = "dashed";
        document.getElementById("images_container").insertBefore(element_outline, old.nextSibling);

        if(!dropObject.last_droppable_element){
            dropObject.last_element_outline = element_outline;
        } else {
            document.getElementById("images_container").removeChild(dropObject.last_element_outline);
            dropObject.last_element_outline = element_outline;
        }

        dropObject.last_index = $(".droppable").index(element_outline);
        dropObject.last_droppable_element = element_outline;

        $(".droppable").each(function (index, element) {
            element.dataset.sortNumber = index;
        });

        dropObject.last_sort_number = element_outline.dataset.sortNumber;

        return avatar;
    }

    function startDrag(e) {
        var avatar = dragObject.avatar;

        // инициировать начало переноса
        document.body.appendChild(avatar);
        avatar.style.zIndex = 9999;
        avatar.style.position = 'absolute';
    }

    function findDroppable(event) {

        for (var i = 0; i < draggable_elements.elements_coordinates_array.length; i++) {

            current_left =  draggable_elements.elements_coordinates_array[i].left;
            current_right = draggable_elements.elements_coordinates_array[i].right;
            current_top = draggable_elements.elements_coordinates_array[i].top;
            current_bottom = draggable_elements.elements_coordinates_array[i].bottom;

            if (current_left <  dragObject.pageX && current_right >  dragObject.pageX && current_top <  dragObject.pageY && current_bottom >  dragObject.pageY){
                return i;
            }
        }
    }

    document.onmousemove = onMouseMove;
    document.onmouseup = onMouseUp;
    document.onmousedown = onMouseDown;

    this.onDragEnd = function(dragObject, dropObject) {
        let myFirstPromise = new Promise((resolve, reject) => {
            // Мы вызываем resolve(...), когда асинхронная операция завершилась успешно, и reject(...), когда она не удалась.
            // В этом примере мы используем setTimeout(...), чтобы симулировать асинхронный код.
            // В реальности вы, скорее всего, будете использовать XHR, HTML5 API или что-то подобное.
            dragObject.avatar.apply_new_parameters();

            resolve("Success!"); // Ура! Всё прошло хорошо!

        });

        myFirstPromise.then((successMessage) => {
            // successMessage - это что угодно, что мы передали в функцию resolve(...) выше.
            // Это необязательно строка, но если это всего лишь сообщение об успешном завершении, это наверняка будет она.
            console.log("Ура! " + successMessage);

            $(".droppable").each(function (index, element) {

                // console.log(index);
                // console.log(element);

                element.dataset.sortNumber = index;

            });
        });
    };

    this.onDragCancel = function(dragObject) {
        dragObject.avatar.rollback();
    };
};


function getCoords(elem) { // кроме IE8-
    var box = elem.getBoundingClientRect();

    return {
        top: box.top + pageYOffset,
        left: box.left + pageXOffset
    };

}