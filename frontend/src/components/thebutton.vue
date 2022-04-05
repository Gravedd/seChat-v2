<template>
    <button><slot></slot></button>
</template>

<script>
export default {
    name: "thebutton"
}
</script>

<style scoped>
button {
    padding: 8px;
    background-color: var(--main-color);
    border: none;
    border-radius: 8px;
    font-size: 14pt;
    color: var(--white-color);
    transition: 0.3s;
    display: inline-flex;
    height: 41px;
}
button:hover {
    filter: brightness(95%);
    transform: scale(1.03);
}
</style>