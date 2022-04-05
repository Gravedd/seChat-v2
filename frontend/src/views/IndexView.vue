<template>
    <div class="container">
        <section class="intro">
            <div class="introwrapper">
                <div class="wrapper50">
                    <h1>seChat</h1>
                    <h2>Безопасный чат</h2>
                    <ul>
                        <li>Сообщения шифруются на вашем устройстве</li>
                        <li>Открытый исходный код</li>
                        <li>Полностью бесплатно</li>
                    </ul>
                </div>
                <div class="wrapper50 right">
                    <h2>Мобильное приложение</h2>
                    <div>Скоро...</div>
                </div>
            </div>
        </section>
        <section class="workinginfo">
            <h2>Как работает чат?</h2>
            <div class="itemcontrainer">
                <div class="item">
                    <div class="icon" style="background-image: url(/icons/send.svg); background-size: 45%"/>
                    <div class="textinfo">Вы отправляете сообщение пользователю</div>
                </div>
                <div class="item">
                    <div class="icon" style="background-image: url(/icons/key.svg)"/>
                    <div class="textinfo">Сообщение шифруется по введеному ВАМИ ключу</div>
                </div>
                <div class="item">
                    <div class="icon" style="background-image: url(/icons/padlock.svg)"/>
                    <div class="textinfo">Сохраняется в нашей базе данных, допольнительно шифруясь</div>
                </div>
                <div class="item">
                    <div class="icon" style="background-image: url(/icons/secure.svg)"/>
                    <div class="textinfo">Адресат получает сообщение в защифрованном виде, и сможет прочитать его только
                        если у него есть ключ
                    </div>
                </div>
            </div>
        </section>
        <section class="siteinfo">
            <h2>Информация о сайте</h2>
            Этот сайт - проект для портфолио.<br>
            Проект разработан на html, css, php(Laravel 8) и javascript.<br>
            В проекте применены технологии: REST API, фрейморк VUE.JS<br>
            Сайт находится в состоянии МVP (Minimum Viable Product) и активной разработки.<br>
            <a href="https://site.com" target='_blank'>Код проекта</a><br>
            <a href="https://site.com" target='_blank'>Сайт разработчика</a><br>
            <small>version: 0.1</small><br>
        </section>
    </div>
    <thefooter></thefooter>


</template>

<script>
import Thefooter from "@/components/thefooter";
export default {
    name: "IndexView",
    components: {Thefooter}
}
</script>

<style scoped>
.intro {
    background-color: var(--main-color);
    height: 300px;
    width: 100%;
    padding: 0 var(--padding);
    color: var(--white-color);
}
.introwrapper {
    display: flex;
    padding-top: 50px;
}
.wrapper50 {
    width: 50%;
}
h1 {
    font-size: 36pt;
}
h2 {
    font-size: 18pt;
}
.right {
    align-self: center;
}
.workinginfo {
    padding: 8px var(--padding);
}
.itemcontrainer {
    display: flex;
}
.itemcontrainer .item {
    width: 25%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.item .icon{
    height: 150px;
    width: 90%;
    background-position: center;
    background-size: 30%;
    background-repeat: no-repeat;
}
.item .textinfo {
    text-align: center;
}
.siteinfo {
    padding: 8px var(--padding);
}
@media (max-width: 626px) {
    .wrapper50 {
        padding: 8px;
    }
    .intro {
        height: auto;
    }
}
@media (max-width: 480px) {
    .introwrapper {
        flex-direction: column;
    }
    .wrapper50 {
        width: 100%;
    }
}
</style>