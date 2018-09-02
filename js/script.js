;(function () {

    // AJAX - код из интернета

    var ajax = {};
    ajax.x = function () {
        if (typeof XMLHttpRequest !== 'undefined') {
            return new XMLHttpRequest();
        }
        var versions = [
            "MSXML2.XmlHttp.6.0",
            "MSXML2.XmlHttp.5.0",
            "MSXML2.XmlHttp.4.0",
            "MSXML2.XmlHttp.3.0",
            "MSXML2.XmlHttp.2.0",
            "Microsoft.XmlHttp"
        ];

        var xhr;
        for (var i = 0; i < versions.length; i++) {
            try {
                xhr = new ActiveXObject(versions[i]);
                break;
            } catch (e) {
            }
        }
        return xhr;
    };

    ajax.send = function (url, callback, method, data, async) {
        if (async === undefined) {
            async = true;
        }
        var x = ajax.x();
        x.open(method, url, async);
        x.onreadystatechange = function () {
            if (x.readyState == 4) {
                callback(x.responseText)
            }
        };
        if (method == 'POST') {
            x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        }
        x.send(data)
    };

    ajax.post = function (url, data, callback, async) {
        var query = [];
        for (var key in data) {
            query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
        ajax.send(url, callback, 'POST', query.join('&'), async)
    };

    // AJAX - код из интернета


    // функция добавляет поле с ошибкой после элемента
    var appendAfter = function(elem, target){
        target.parentNode.insertBefore(elem, target.nextSibling);
    };

    // функция создаёт блок с ошибкой
    var createMessage = function(requiredMessage){
            var message = document.createElement('span');
            message.classList.add('form-message');
            message.innerHTML = requiredMessage;
            return message;
    };

    // функция проверяет корретность данных
    var checkFormat = function(type, value){
        var regMail = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
               regPhone = /^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/im;
           switch (type){
               case 'email':
                    return regMail.test(value.toLowerCase());
                    break;
                case 'tel':
                    return regPhone.test(value.toLowerCase());
                    break;
                default:
                    return true;
                    break;
            }
    };

    var form = document.querySelector('#form');
    var inputs = form.querySelectorAll('input:not([type="submit"]), textarea');

    // функция создаёт и добавляет сообщениия об ошибках
    var addMessages = function(messages){
            for(var messageElement in messages){
                var currentMessage = messages[messageElement],
                    messageDOM = createMessage(currentMessage.message);
                appendAfter(messageDOM, inputs[currentMessage.index]);
            }
        };


    form.addEventListener('submit', function(event){
        event.preventDefault();
        var messages = form.querySelectorAll('.form-message'),
            inputsToServer = [];

        for(var j = 0; j < messages.length; j++){
            if(messages[j] && messages[j].parentNode) messages[j].parentNode.removeChild(messages[j]);
        }

        var errors = 0;
        for(var i= 0; i < inputs.length; i++){
            (function(i){
                var currentItem = inputs[i],
                    requiredMessage = currentItem.getAttribute('data-empty'),
                    formatedMessage = currentItem.getAttribute('data-notformated'),
                    type = currentItem.type,
                    value = currentItem.value,
                    message = "";
                inputsToServer.push({
                    name: currentItem.name,
                    type: type,
                    value: value,
                    index: i
                });
                if(requiredMessage && !value.length){
                    message = createMessage(requiredMessage);
                    appendAfter(message, currentItem);
                    errors++;
                }
                if(value.length && formatedMessage && !checkFormat(type, value)) {
                    message = createMessage(formatedMessage);
                    appendAfter(message, currentItem);
                    errors++;
                }
            })(i);
        }
        if(errors){
            return false;
        }

        ajax.post('./check.php', {
            inputs: JSON.stringify(inputsToServer)
        },function(response){
            if(response === 'success'){
                alert('Success')
            }
            else{
                JSON.parse(response) && addMessages(JSON.parse(response));
            }
        });
    });
})();