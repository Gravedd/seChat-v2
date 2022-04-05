<template>
    <section class="friends module">
        <h1>Мои друзья</h1>
        <hr>
        <div class="friendscontainer">
            <div class="friendswrapper">
                <h3 class="name">@nickname</h3>
                <div class="actions">
                    <iconbutton image="/icons/interface/new-message.svg">Перейти в диалог</iconbutton>
                    <iconbutton image="/icons/interface/remove.svg" class="red">Удалить из друзей</iconbutton>
                </div>
            </div>
            <div class="friendswrapper">
                <h3 class="name">@nickname</h3>
                <div class="actions">
                    <iconbutton image="/icons/interface/new-message.svg">Перейти в диалог</iconbutton>
                    <iconbutton image="/icons/interface/remove.svg" class="red">Удалить из друзей</iconbutton>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Iconbutton from "@/components/iconbutton";
export default {
    name: "friends",
    components: {Iconbutton}
}
</script>

<style scoped>
.friends {
    width: 100%;
    padding: 16px;
}
hr {
    margin: 8px 0;
}
h3 {
    color: var(--main-color);
}
.friendswrapper {
    width: 100%;
    padding: 8px;
    border-radius: 20px;
    margin-bottom: 8px;
}
.actions {
    padding: 8px 0px;
}
@media (max-width: 578px) {
    .actions .btn {
        margin-bottom: 8px;
    }
    .friendswrapper {
        text-align: center;
    }
}

</style>