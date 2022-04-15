const alerthtml = `
    <div class="alertwrapper">
        <h2 id="alertheader">Alert header</h2>
        <div id="alerttext">
            alert text
        </div>
        <button id="okbtn">OK</button>
    </div>
`;
function showalert(header, text)
{
    let alertwindow = document.createElement('div');
    alertwindow.id = "alertbg";
    document.body.prepend(alertwindow);
    alertwindow.title = header;
    alertwindow.innerHTML = alerthtml;//распологаем элемент
    //Изменяем текст в окне
    document.getElementById('alertheader').innerHTML = header;
    document.getElementById('alerttext').innerHTML = text;
    //Кнопка
    let okbtn = document.getElementById('okbtn');
    if (header === 'Успешно!' || header === 'Успешно') {
        okbtn.classList.add('success');
    }
    okbtn.title = header;
    okbtn.addEventListener('click', closealert);
}

function closealert() {
    let title = this.title;
    console.log(title);
    let alertwindow = document.querySelector('#alertbg[title="' + title + '"]');
    alertwindow.remove();
}
