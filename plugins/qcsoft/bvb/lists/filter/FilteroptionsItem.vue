<template>
    <div>
        <b-button ref="openControlsBtn"
                 v-text="item.name"
                 tabindex="0"
                  class="p-0 px-1"
        ></b-button>
        <b-popover :target="() => $refs.openControlsBtn"
                   placement="left"
                   triggers="click blur"
                   :show.sync="isControlsOpen"
        >
            <template v-slot:title>
                <b-button @click="isControlsOpen = false" class="close" aria-label="Close">
                    <span class="d-inline-block" aria-hidden="true">&times;</span>
                </b-button>
                Filter option
            </template>
            <b-input v-model="item.name"></b-input>
<!--            <div v-text="item"></div>-->
        </b-popover>
    </div>
</template>
<script>
    export default {
        props: ['cell', 'item'],
        data : vm => ({
            isControlsOpen: false,
        }),
    }
</script>
<style scoped lang="scss">
    .badge {
        font-size: 100%;
        font-weight: normal;
        cursor: pointer;
    }
</style>
