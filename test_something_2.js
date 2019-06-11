
var DragManager = new function() {

    /**
     * составной объект для хранения информации о переносе:
     * {
   *   elem - элемент, на котором была зажата мышь
   *   avatar - аватар
   *   downX/downY - координаты, на которых был mousedown
   *   shiftX/shiftY - относительный сдвиг курсора от угла элемента
   * }
     */
    var dragObject = {};
    var dropObject = {};

    var self = this;

    function onMouseDown(e) {

        if (e.which != 1) return;

        var elem = e.target.closest('.draggable');
        if (!elem) return;

        dragObject.elem = elem;

        // запомним, что элемент нажат на текущих координатах pageX/pageY
        dragObject.downX = e.pageX;
        dragObject.downY = e.pageY;

        return false;
    }

    function onMouseMove(e) {
        if (!dragObject.elem) return; // элемент не зажат

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


        if(dropObject.last_droppable_element){
            last_droppable_element = dropObject.last_droppable_element;
        } else {
            last_droppable_element = document.createElement("div");
            last_droppable_element.innerHTML = 'last_droppable_element';
        }

        var elem_droppable = findDroppable(e);

        if (elem_droppable) {

            if(elem_droppable.innerHTML !== last_droppable_element.innerHTML) {

                console.log('ветка 1 (droppable элементы не равны, создается новый element_outline)');
                console.log("%c last_droppable_element", "color: green");
                console.log(last_droppable_element);
                console.log("%c last_droppable_element.nextSibling", "color: green");
                console.log(last_droppable_element.nextSibling);
                console.log("%c elem_droppable", "color: green");
                console.log(elem_droppable);

                var element_outline = document.createElement("div");
                element_outline.className = "element shape";
                element_outline.style.borderColor = "red";
                element_outline.style.borderStyle = "dashed";

                if(dropObject.last_droppable_element){

                    if(dropObject.last_droppable_element.innerHTML === element_outline.innerHTML){
                        console.log("ПОСЛЕДНИЙ droppable ЭЛЕМЕНТ = ОБРАЗ");

                        last_droppable_element.style.backgroundColor = 'red';

                        if(dropObject.last_droppable_element.nextSibling.innerHTML === elem_droppable.innerHTML){
                            console.log('%c СДЕЛАН ШАГ ВПЕРЕД', 'color: red;');
                            document.getElementById("images_container").insertBefore(element_outline, elem_droppable.nextSibling);
                        } else {
                            console.log('%c СДЕЛАН ШАГ НАЗАД', 'color: red;');
                            document.getElementById("images_container").insertBefore(element_outline, elem_droppable);
                        }

                    } else {
                        console.log("ПОСЛЕДНИЙ droppable ЭЛЕМЕНТ !!!= ОБРАЗ");
                        document.getElementById("images_container").insertBefore(element_outline, elem_droppable);
                    }

                } else {
                    document.getElementById("images_container").insertBefore(element_outline, elem_droppable);
                }

                if(!dropObject.last_droppable_element){
                    dropObject.last_droppable_element = elem_droppable;
                    dropObject.last_element_outline = element_outline;
                } else {
                    document.getElementById("images_container").removeChild(dropObject.last_element_outline);

                    elem_droppable = document.getElementsByClassName('element shape')[0];

                    dropObject.last_droppable_element = elem_droppable;
                    dropObject.last_element_outline = element_outline;

                    console.log('%c last_droppable_element', 'color: blue;');
                    console.log(dropObject.last_droppable_element);

                    console.log('%c ВОТ КАКОЙ СЛЕДУЮЩИЙ ЭЛЕМЕНТ У last_droppable_element', 'color: blue;');
                    console.log(dropObject.last_droppable_element.nextSibling);
                }

            } else {
                console.log('ветка 2 (droppable элементы равны)');
            }
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
        var elem_droppable = findDroppable(e);

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
        element_outline.className = "element shape";
        element_outline.style.borderColor = "red";
        element_outline.style.borderStyle = "dashed";
        document.getElementById("images_container").insertBefore(element_outline, old.nextSibling);
        if(!dropObject.last_droppable_element){
            dropObject.last_element_outline = element_outline;
        } else {
            document.getElementById("images_container").removeChild(dropObject.last_element_outline);
            dropObject.last_element_outline = element_outline;
        }

        dropObject.last_droppable_element = element_outline;

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

        // спрячем переносимый элемент
        dragObject.avatar.style.visibility = 'hidden';
        // dragObject.avatar.hidden = true;

        // получить самый вложенный элемент под курсором мыши
        var elem = document.elementFromPoint(event.clientX, event.clientY);

        if(dropObject.last_element_outline){
            if(elem.innerHTML  === dropObject.last_element_outline.innerHTML){
                elem.classList.add('droppable')
            }
        }

        elem_droppable = elem.closest('.droppable');

        // показать переносимый элемент обратно
        dragObject.avatar.style.visibility = 'visible';
        // dragObject.avatar.hidden = false;

        if(elem_droppable){
            var new_parameters = {
                parent: elem_droppable.parentNode,
                // nextSibling: elem_droppable.nextSibling,
                nextSibling: elem_droppable,
                position: elem_droppable.position || '',
                left: elem_droppable.left || '',
                top: elem_droppable.top || '',
                zIndex: elem_droppable.zIndex || ''
            };
        }

        if(new_parameters){
            dragObject.elem.apply_new_parameters = function() {
                new_parameters.parent.insertBefore(dragObject.elem, new_parameters.nextSibling);
                dragObject.elem.style.position = new_parameters.position;
                dragObject.elem.style.left = new_parameters.left;
                dragObject.elem.style.top = new_parameters.top;
                dragObject.elem.style.zIndex = new_parameters.zIndex
            };
        }
        
        if (elem == null) {
            // такое возможно, если курсор мыши "вылетел" за границу окна
            return null;
        }

        dropObject.elem_droppable = elem_droppable;

        return elem_droppable;
    }

    document.onmousemove = onMouseMove;
    document.onmouseup = onMouseUp;
    document.onmousedown = onMouseDown;

    this.onDragEnd = function(dragObject, dropObject) {};
    this.onDragCancel = function(dragObject) {};

};


function getCoords(elem) { // кроме IE8-
    var box = elem.getBoundingClientRect();

    return {
        top: box.top + pageYOffset,
        left: box.left + pageXOffset
    };

}