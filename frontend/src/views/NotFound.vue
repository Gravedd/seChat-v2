<template>
<div class="container">
    <div class="error module">
        <h1>Ошибка 404</h1>
        <div>Указанная страница не найдена</div>
        <router-link to="/">Вернутся на главную</router-link>
    </div>
</div>
</template>

<script>
export default {
    name: "NotFound"
}
</script>

<style scoped>
.container {
    display: flex;
    padding: 8px var(--padding);
    align-items: center;
    justify-content: center;
}
.container .error {
    display: inline-block;
    padding: 32px;
    font-size: 32pt;
    text-align: center;
}
.container .error h1 {
    color: var(--main-color);
}
.error div {
    font-size: 28pt;
    font-family: LightFont;
}
a {
    font-size: 20pt;
    font-family: LightFont;
    color: var(--accent-color);
}
@media (max-width: 454px) {
    .container .error {
        font-size: 12pt;
    }
    
}
</style>