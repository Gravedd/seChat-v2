<template>
    <div class="container">
        <h1>Поиск пользователей</h1>
        <div class="searchcontainer">
            <div class="module">
                <form class="searchform">
                    <input type="text" maxlength="64" placeholder="Введите ник пользователя...">
                    <iconbutton title="Найти" nopadding="true" image="/icons/interface/search.svg"></iconbutton>
                </form>
            </div>
            <div class="searchresults">
                <div class="module">
                    <h3>@nickname</h3>
                    <div class="text">text here</div>
                </div>
                <div class="module">
                    <h3>@nickname</h3>
                    <div class="text">text here</div>
                </div>
                <div class="module">
                    <h3>@nickname</h3>
                    <div class="text">text here</div>
                </div>
                <div class="module">
                    <h3>@nickname</h3>
                    <div class="text">text here</div>
                </div>
                <div class="module">
                    <h3>@nickname</h3>
                    <div class="text">text here</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
export default {
    name: "SearchView",
    components: {Iconbutton}
}
</script>

<style scoped>
.container {
}

h1 {
    background-color: var(--main-color);
    text-align: center;
    padding: 16px;
    color: var(--white-color);
    margin-bottom: 8px;
}
.searchcontainer {
    padding: 8px var(--padding);
}
.searchcontainer .module {
    padding: 16px;
    margin-bottom: 24px;
}
.searchform {
    display: flex;
}
.searchform input {
    width: 90%;
    height: 50px;
    border-radius: 16px 0 0 16px;
    border: 2px solid var(--main-color);
    background-color: var(--gray4);
    padding: 16px;
    font-size: 12pt;
}
.searchform input:focus {
    outline: none;
    box-shadow: 0 0 3px 0 var(--main-color);
}
.searchform .btn {
    border-radius: 0 16px 16px 0;
    width: 100px;
    justify-content: center;
}
.searchform .btn:hover {
    transform: none;
}
.searchresults .module h3 {

}
.searchresults .module .text {
    font-family: LightFont;
}
@media (max-width: 540px) {
    h1 {
        font-size: 150%;
    }
}
@media (max-width: 410px) {
    h1 {
        font-size: 100%;
    }
}

</style>