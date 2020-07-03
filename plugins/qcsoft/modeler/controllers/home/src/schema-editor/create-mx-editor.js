import cellStyles from "./cell-styles";
import editClasses from './edit-classes.js'

export default {
    methods: {
        createMxEditor()
        {
            let editor = new mxEditor()

            editor.graph.setCellsEditable(false)

            editor.graph.setHtmlLabels(true)

            cellStyles(editor.graph)

            ////////////////////////////////////////////////////////////////////////////////
            // Cell labels
            ////////////////////////////////////////////////////////////////////////////////
            mxCell.prototype.setValue = function (value)
            {
                this.value.mxCellSetValue(value)
            }

            mxCell.prototype.getValue = function ()
            {
                return this.value.mxCellGetValue()
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Selection: mx -> vue
            ////////////////////////////////////////////////////////////////////////////////
            editor.graph.getSelectionModel()
                .addListener(mxEvent.CHANGE, (selectionModel, evt) =>
                {
                    editClasses.forEach(cls =>
                    {
                        this.editor.selection[cls.pl] =
                            selectionModel.cells
                                .filter(c => c.value.type === cls.name)
                                .map(c => c.value[cls.name].id)
                    })
                })

            ////////////////////////////////////////////////////////////////////////////////
            // Coordinates: mx -> vue
            ////////////////////////////////////////////////////////////////////////////////
            editor.graph.addListener(mxEvent.MOVE_CELLS, (graph, eventObject) =>
            {
                this.isMxListenerRunning = true

                let p = eventObject.properties

                for (let i = 0; i < p.cells.length; i++)
                {
                    let v = p.cells[i].value

                    if (v.type !== 'entity')
                    {
                        continue
                    }
                    // console.log('setxy')

                    v.entity.x = parseInt(v.entity.x) + parseInt(p.dx)
                    v.entity.y = parseInt(v.entity.y) + parseInt(p.dy)
                }

                this.$nextTick(() =>
                {
                    this.isMxListenerRunning = false
                })
            })

            ////////////////////////////////////////////////////////////////////////////////
            // Movable
            ////////////////////////////////////////////////////////////////////////////////
            editor.graph.isCellMovable = function (cell)
            {
                return cell.style === 'entity'
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Hide collapse/expand button in entity header
            ////////////////////////////////////////////////////////////////////////////////
            editor.graph.foldingEnabled = false

            ////////////////////////////////////////////////////////////////////////////////
            //
            ////////////////////////////////////////////////////////////////////////////////
            // editor.graph.cellRenderer.createLabel = function (state, value)
            // {
            //     // console.log('cellRenderer.createLabel', arguments)
            //
            //     let result = mxCellRenderer.prototype.createLabel.apply(this, arguments);
            //     // console.log(result)
            //
            //     return result
            // }

            ////////////////////////////////////////////////////////////////////////////////
            // Auto resize entity to fit its attributes
            ////////////////////////////////////////////////////////////////////////////////
            // mxSwimlane.imageSize = 1;
            editor.layoutSwimlanes = true
            editor.createSwimlaneLayout = function ()
            {
                let layout = new mxStackLayout(this.graph, false)

                layout.fill = true
                layout.resizeParent = true
                layout.isVertexMovable = () => true

                return layout
            }

            ////////////////////////////////////////////////////////////////////////////////
            // Zoom on mouse wheel, code is taken from here:
            // https://github.com/jgraph/mxgraph/issues/418#issuecomment-631013116
            ////////////////////////////////////////////////////////////////////////////////
            mxEvent.addMouseWheelListener((evt, up) =>
            {
                if (!evt.path.some(i => i === this.$refs.container))
                {
                    return
                }

                if (mxEvent.isConsumed(evt))
                {
                    return
                }

                let gridEnabled = editor.graph.gridEnabled

                // disable snapping
                editor.graph.gridEnabled = false

                let p1 = editor.graph.getPointForEvent(evt, false)

                if (up)
                {
                    editor.graph.zoomIn()
                }
                else
                {
                    editor.graph.zoomOut()
                }

                let p2 = editor.graph.getPointForEvent(evt, false)
                let deltaX = p2.x - p1.x
                let deltaY = p2.y - p1.y
                let view = editor.graph.view

                view.setTranslate(view.translate.x + deltaX, view.translate.y + deltaY)

                editor.graph.gridEnabled = gridEnabled

                // mxEvent.consume(evt)
            })

            return editor

            //
            //
            //
            //
            //
            // editor.graph.setConnectable(true)
            // mxConnectionHandler.prototype.connect = function(source, target, evt, dropTarget)
            // {
            //     console.log(arguments)
            // }
            // mxConnectionHandler.prototype.mouseDown = function(sender, me){
            //     console.log('asd')
            // }
            //
            // mxGraph.prototype.createEdge = function(parent, id, value, source, target, style)
            // {
            //     console.log(arguments)
            // }
            // mxConnectionHandler.prototype.createEdge = function (value, source, target, style)
            // {
            //     console.log(arguments)
            //
            //     var edge = new mxCell(value || '');
            //     edge.setEdge(true);
            //     edge.setStyle(style);
            //
            //     var geo = new mxGeometry();
            //     geo.relative = true;
            //     edge.setGeometry(geo);
            //
            //     return edge
            // }
            //
            //
            //
            //
            //
            //
            //
            //
            //
            //

        }
    }
}
