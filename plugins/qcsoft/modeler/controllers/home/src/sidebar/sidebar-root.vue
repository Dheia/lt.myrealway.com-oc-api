<template>
    <div>
        <div class="sidebar-root">
            <div class="root-toolbar">
                <div class="left-side">
                    <btn @click="onAddEntity" class="btn-add-entity oc-icon-plus">Entity</btn>
                </div>
                <div class="right-side">
                </div>
            </div>
            <div class="root-entities">
                <div v-for="(entity, i) in editor.entities" class="root-entity">
                    <div class="entity-header">
                        <div v-text="entity.id" class="entity-id"></div>
                        <input v-model="entity.name" class="entity-name"/>
                        <div class="entity-header-toolbar">
                            <btn @click="onAddAttribute(entity.id)" class="btn-add-attribute oc-icon-plus"></btn>
                            <btn @click="onDropEntity(i)" class="btn-drop-entity oc-icon-trash"></btn>
                        </div>
                    </div>
                    <div class="entity-attributes">
                        <div v-for="attribute in editor.attributes.filter(v => v.entity_id === entity.id)"
                             class="entity-attribute"
                        >
                            <div v-text="attribute.id" class="attribute-id"></div>
                            <div class="separator"></div>
                            <input v-model="attribute.name" class="attribute-name"/>
                            <div class="separator"></div>
                            <input v-model="attribute.type" class="attribute-type"/>
                            <div class="separator"></div>
                            <btn @click="onAttributeNullable(attribute)"
                                 class="btn-attribute-nullable"
                                 :class="{'is-on': attribute.nullable}"
                            >N
                            </btn>
                            <btn @click="onDropAttribute(attribute.id)" class="btn-drop-attribute oc-icon-trash"></btn>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props  : ['editor'],
        data   : vm => ({
            openEntityIds: [],
            filters      : {
                type    : '',
                nullable: null,
            },
        }),
        methods: {
            onAddEntity()
            {
                this.editor.entities.push({
                    name   : '',
                    x      : 100,
                    y      : 100,
                    width  : 200,
                    height : 100,
                    options: [],
                })
            },
            onAddAttribute(entity_id)
            {
                this.editor.attributes.push({
                    entity_id,
                    name: '',
                    type: '',
                })
            },
            onDropEntity(index)
            {
                this.editor.entities.splice(index, 1)
            },
            onDropAttribute(id)
            {
                let index = this.editor.attributes.findIndex(v => v.id === id)
                console.log(`onDropAttribute ${id}, ${index}`)
                // this.editor.attributes.splice(index, 1)
            },
            onAttributeNullable(attribute)
            {
                attribute.nullable = !attribute.nullable
            },
        },
    }
</script>
<style scoped lang="scss">
    .entity-name, .attribute-name, .attribute-type {
        background: transparent;
        margin: 0;
        border: 0;
        padding: 0 8px;
        width: 0;

        &:focus {
            box-shadow: 0 0 5px 0 #cef;
            border: 0;
            background: rgba(0, 0, 0, .1);
        }

        &:hover {
            background: rgba(0, 0, 0, .1);
        }
    }

    .btn-add-entity, .btn-add-attribute, .btn-drop-entity, .btn-attribute-nullable, .btn-drop-attribute {
        margin: 0 0 0 1px;
        padding: 2px 7px 0 7px;
        /*border: 0;*/
        box-shadow: none;
        background: rgba(0, 0, 0, .05);
        color: #005;
        display: block;

        &:hover {
            background: rgba(0, 0, 0, .1);
        }

        &::before {
            margin-right: 0;
            line-height: 150%;
            font-size: 150%;
        }
    }

    .btn-add-entity {
        &::before {
            vertical-align: -1px;
            margin-right: 7px;
        }
    }

    .btn-attribute-nullable {
        background: #fd9;

        &:hover {
            background: #bfc;
        }

        &.is-on {
            background: #bfb;

            &:hover {
                background: #fd9;
            }
        }
    }

    .btn-drop-entity, .btn-drop-attribute {
        &::before {
            color: #500;
        }
    }

    .btn-drop-attribute {
        background: transparent;

        &::before {
            line-height: 140%;
            font-size: 140%;
        }
    }

    .entity-id, .attribute-id {
        margin-top: -1px;
        font-weight: bold;
    }

    .sidebar-root {
        position: absolute;
        left: 0;
        top: 40px;
        right: 0;
        bottom: 0;
    }

    .root-toolbar {
        height: 40px;
        display: flex;
        align-items: center;
        box-shadow: 0 2px 3px -2px rgba(0, 0, 0, 0.5);
        padding: 0 0 5px 0;

        .left-side {
            padding: 0 0 0 7px;
            flex-shrink: 0;
            flex-grow: 1;
        }

        .right-side {
            padding: 0 7px 0 0;
            flex-shrink: 0;
            flex-grow: 0;
        }
    }

    .root-entities {
        position: absolute;
        left: 0;
        top: 45px;
        right: 0;
        bottom: 0;
        overflow: scroll;
    }

    .root-entity {
        border-bottom: 1px solid rgba(0, 0, 0, .1);
        padding: 5px 5px 5px 15px;
    }

    .entity-header {
        display: flex;
        align-items: baseline;

        .entity-name {
            flex-grow: 1;
            align-self: stretch;
        }

        .entity-id {
            min-width: 20px;
        }
    }

    .entity-header-toolbar {
        display: flex;
    }

    .entity-attributes {
        margin-left: 26px;
    }

    .entity-attribute {
        display: flex;
        align-items: baseline;

        .separator {
            border-left: 1px solid #ccc;
            width: 1px;
            flex-grow: 0;
            flex-shrink: 0;
        }

        .attribute-id {
            width: 35px;
            flex-grow: 0;
            flex-shrink: 0;
        }

        .attribute-name, .attribute-type {
            align-self: stretch;
            flex-shrink: 1;
        }

        .attribute-name {
            flex-grow: .65;
        }

        .attribute-type {
            flex-grow: .35;
        }
    }
</style>
