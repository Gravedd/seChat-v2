<template>
<div class="container">
    <div class="dialogueswrapper">
        <div class="leftpanel">
            <div class="userwrapper module">
                <router-link to="/dialogues/1">
                    <h3>@nickname</h3>
                    <div>Последнее сообщение</div>
                </router-link>
            </div>
            <div class="userwrapper module">
                <router-link to="/dialogues/2">
                    <h3>@nickname</h3>
                    <div>Последнее сообщение</div>
                </router-link>
            </div>
            <div class="userwrapper module">
                <router-link to="/dialogues/3">
                    <h3>@nickname</h3>
                    <div>Последнее сообщение</div>
                </router-link>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
import IndexView from "@/views/IndexView";
export default {
    name: "DialoguesView",
    components: {IndexView, Iconbutton},
    created() {
        //
    }
}
</script>

<style scoped>
.container {
    min-height: auto;
}
.dialogueswrapper {
    display: flex;
    padding: 8px var(--padding);
    justify-content: space-between;
}
.leftpanel {
    width: 100%;
    height: auto;
}
.userwrapper {
    padding: 16px;
    transition: 0.5s;
    margin-bottom: 8px;
    width: 99%;
}
.userwrapper:hover {
    transform: scale(1.02);
    transition: 0.25s;
}
</style>