<template>
    <div class="btn">
        <img v-bind:src="image" width="25" v-bind:class="{nopaddingclass: nopadding}">
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "iconbutton",
    data() {
        return{

        }
    },
    props: {
        image: String,
        nopadding: String,
    }
}
</script>

<style scoped>
.btn {
    padding: 8px;
    color: var(--white-color);
    background-color: var(--main-color);
    border-radius: 8px;
    font-size: 14pt;
    display: inline-flex;
    transition: 0.3s;
    margin-right: 8px;
    font-family: "LightFont";
}
img {
    margin-right: 8px;
}
.nopaddingclass {
    margin-right: 0px;
}
.btn:hover {
    filter: brightness(95%);
    transform: scale(1.03);
}
.disabled {
    background-color: var(--gray3);
}
.disabled:hover {
    transform: none;
}
</style>