<template>
    <div>
        <b-button v-text="cell.value.map(v => v.name).join(', ')"
                  variant="link"
                  class="px-0"
                  ref="openControlsBtn"
                  tabindex="0"
        />
        <b-popover :target="() => $refs.openControlsBtn"
                   placement="left"
                   triggers="click blur"
                   :show.sync="isControlsOpen"
        >
            <template v-slot:title>
                <b-button @click="isControlsOpen = false" class="close" aria-label="Close">
                    <span class="d-inline-block" aria-hidden="true">&times;</span>
                </b-button>
                {{ cell.item.name }} options
            </template>
            <b-button variant="primary" class="mb-1 pl-2 pr-3" @click="add">
                <b-icon icon="plus"/>
                Add
            </b-button>
            <div class="mb-1">
                <div v-for="(fopt, i) in localItems" class="py-1 d-flex flex-nowrap">
                    <b-input v-model="fopt.name" class="flex-grow-1 flex-shrink-1"></b-input>
                    <b-button variant="link" class="flex-grow-0 flex-shrink-0" @click="remove(i)">
                        <b-icon icon="trash"/>
                    </b-button>
                </div>
            </div>
            <b-button variant="primary" class="mb-1 pl-2 pr-3" @click="cancel">
                <b-icon icon="x"/>
                Cancel
            </b-button>
            <b-button variant="primary" class="mb-1 pl-2 pr-3" @click="save">
                <b-icon icon="box-arrow-in-up-right" class="mr-1"/>
                Save
            </b-button>
        </b-popover>
        <!--<filteroptions-item :cell="cell"
                            :item="item"
                            v-for="item in cell.value"
                            style="display: inline-block; margin: 0 5px 0 0"
        ></filteroptions-item>
        <b-button variant="primary" class="p-0">
            <b-icon-plus/>
        </b-button>-->
    </div>
</template>
<script>
    import FilteroptionsItem from './FilteroptionsItem.vue'

    export default {
        components: {FilteroptionsItem},
        props     : ['cell'],
        data      : vm => ({
            localItems    : vm.cell.value.map(v => ({...v})),
            isControlsOpen: false,
        }),
        methods   : {
            add()
            {
                this.localItems.unshift({name: ''})
            },
            remove(index)
            {
                this.localItems.splice(index, 1)
                this.$refs.openControlsBtn.focus()
            },
            cancel()
            {
            },
            save()
            {
            },
        },
    }
</script>
<style lang="scss">
    .lists-filter-filteroptions-popover {
        .popover-body {
            padding-right: 0;
        }
    }
</style>
