
//counter row in section 4 UMK
var i=1;

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

//delete row in section 4 UMK
function del(s) {
    $("#"+s).remove();
    
}



//add new row in UMK section 4
$("#add").click( function() {
    i++;
    var content='<div class="form-inline mb-1" id="r'+i+'"> ';
    content=content+'<input type="text" class="mr-1 mb-1" size="70" name="disc[]" placeholder="Раздел дисциплины"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="sem[]" placeholder="Семестр" title="Номер семестра"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="ned[]" placeholder="Неделя" title="Неделя семестра"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="lek[]" placeholder="Лекции" title="Количество часов лекций"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="prak[]" placeholder="Практика" title="Количество часов практических занятий"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="lr[]" placeholder="ЛР" title="Количество часов лабораторных работ"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="kr[]" placeholder="КР" title="Количество часов контрольных работ"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="kp[]" placeholder="КП/КР"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="sr[]" placeholder="СР"  title="Количество часов самостоятельных работ работ"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="5" name="im[]" placeholder="ИМ" title="Объём учебной работы с применением интерактивных методов"/>';
    content=content+'<input type="text" class="mr-1 mb-1" size="15" name="kontr[]" placeholder="Контроль" title="Формы текущего контроля успеваемости"/>';
    content=content+'<button class="btn btn-danger" onclick="del(\'r'+i+'\');return false;">Удалить раздел</button>';
    content=content+'</div>';
    
    $("#struct").append(content);
    
    return false;
});

