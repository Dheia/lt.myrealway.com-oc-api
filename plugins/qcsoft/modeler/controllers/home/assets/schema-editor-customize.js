window.schemaEditorCustomize = function (editor)
{
    let graph = editor.graph,
        container = graph.container

    // https://github.com/jgraph/mxgraph/issues/418#issuecomment-631013116
    mxEvent.addMouseWheelListener((evt, up) =>
    {
        if (mxEvent.isConsumed(evt))
        {
            return;
        }

        let gridEnabled = graph.gridEnabled;

        // disable snapping
        graph.gridEnabled = false;

        let p1 = graph.getPointForEvent(evt, false);

        if (up)
        {
            graph.zoomIn();
        }
        else
        {
            graph.zoomOut();
        }

        let p2 = graph.getPointForEvent(evt, false);
        let deltaX = p2.x - p1.x;
        let deltaY = p2.y - p1.y;
        let view = graph.view;

        view.setTranslate(view.translate.x + deltaX, view.translate.y + deltaY);

        graph.gridEnabled = gridEnabled;

        mxEvent.consume(evt);
    }, container);

    // Ctrl + S
    document.addEventListener('keydown', function (e)
    {
        if (e.key === 's' && e.ctrlKey)
        {
            e.preventDefault()

            var enc = new mxCodec(mxUtils.createXmlDocument());
            var node = enc.encode(editor.graph.getModel());

            $.request('onUpdateGraph', {
                data: {
                    xml: mxUtils.getPrettyXml(node),
                },
                success(data, textStatus, jqXHR)
                {
                    // console.log(arguments)
                },
            })
        }
    })

}