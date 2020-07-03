<template>
    <div>
        <div class="sidebar-entity">
            <div class="entity-header">
                <div v-text="entity.id" class="entity-id"></div>
                <input v-model="entity.name" class="entity-name"/>
                <div class="entity-header-toolbar">
                </div>
            </div>

            <div class="entity-settings">
                <div class="entity-xy">
                    <div class="x-label">x</div>
                    <input v-model="entity.x" class="entity-x"/>
                    <div class="y-label">y</div>
                    <input v-model="entity.y" class="entity-y"/>
                </div>
                <div class="entity-size">
                    <div class="w-label">width</div>
                    <input v-model="entity.width" class="entity-w"/>
                    <div class="h-label">height</div>
                    <input v-model="entity.height" class="entity-h"/>
                </div>
            </div>
            <div class="entity-options">
                <div v-for="option in entity.options" class="entity-option">
                    <div v-text="option.type" class="option-name"></div>
                    <div v-text="option.name" class="option-name"></div>
                    <div v-text="option.value" class="option-value"></div>
                </div>
            </div>
            <btn @click="addAttribute(entity.id)" class="btn-add-attribute">+</btn>
            <div class="entity-attributes">
                <div v-for="attribute in entityAttributes"
                     class="entity-attribute"
                >
                    <div v-text="attribute.id" class="attribute-id"></div>
                    <div class="separator"></div>
                    <input v-model="attribute.name" class="attribute-name"/>
                    <div class="separator"></div>
                    <input v-model="attribute.type" class="attribute-type"/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props   : ['editor', 'entity'],
        computed: {
            entityAttributes: vm => vm.editor.attributes.filter(v => v.entity_id === vm.entity.id),
        },
        methods : {
            addAttribute()
            {
                this.editor.attributes.push({
                    entity_id: this.entity.id,
                    name     : '',
                    type     : '',
                })
            },
        },
    }
</script>
<style scoped lang="scss">
    .entity-name, .entity-x, .entity-y, .entity-w, .entity-h,
    .attribute-name, .attribute-type {
        background: transparent;
        margin: 0;
        border: 0;
        padding: 0 8px;

        &:focus {
            box-shadow: 0 0 5px 0 #cef;
            border: 0;
            background: rgba(0, 0, 0, .1);
        }

        &:hover {
            background: rgba(0, 0, 0, .1);
        }
    }

    .entity-id, .attribute-id {
        font-weight: bold;
    }

    .entity-id {
        min-width: 20px;
    }

    .sidebar-entity {
        position: absolute;
        left: 0;
        top: 40px;
        right: 0;
        bottom: 0;
    }

    .entity-header {
        display: flex;
        margin: 10px 0 0 0;
        align-items: baseline;

        .entity-name {
            flex-grow: 1;
        }
    }

    .entity-settings {
        margin: 10px 0 0 0;

        .entity-xy, .entity-size {
            display: flex;
            align-items: baseline;
        }

        .x-label, .y-label, .w-label, .h-label {
            width: 50px;
            flex-shrink: 0;
        }

        .entity-x, .entity-y, .entity-w, .entity-h {
            flex-grow: 1;
        }
    }

    .entity-options {
    }

    .entity-option {
        display: flex;
        border: 1px solid black;

        .option-name {
        }

        .option-value {
        }
    }

    .btn-add-attribute {
        padding: 3px 8px;
        margin: 15px 0 5px 0;
    }

    .entity-attributes {
    }

    .entity-attribute {
        display: flex;
        align-items: stretch;

        .separator {
            border-left: 1px solid #ccc;
        }

        .attribute-id {
            min-width: 35px;
        }

        .attribute-name {
            flex-grow: 1;
        }

        .attribute-type {
        }
    }
</style>
