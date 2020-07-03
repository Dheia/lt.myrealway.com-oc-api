export default {
    addListener(self)
    {
        ////////////////////////////////////////////////////////////////////////////////
        // Zoom on mouse wheel, code is borrowed from here:
        // https://github.com/jgraph/mxgraph/issues/418#issuecomment-631013116
        ////////////////////////////////////////////////////////////////////////////////
        mxEvent.addMouseWheelListener((evt, up) =>
        {
            if (mxEvent.isConsumed(evt))
            {
                return
            }

            let gridEnabled = self.graph.gridEnabled

            // disable snapping
            self.graph.gridEnabled = false

            let p1 = self.graph.getPointForEvent(evt, false)

            if (up)
            {
                self.graph.zoomIn()
            }
            else
            {
                self.graph.zoomOut()
            }

            let p2 = self.graph.getPointForEvent(evt, false)
            let deltaX = p2.x - p1.x
            let deltaY = p2.y - p1.y
            let view = self.graph.view

            view.setTranslate(view.translate.x + deltaX, view.translate.y + deltaY)

            self.graph.gridEnabled = gridEnabled

            mxEvent.consume(evt)

        }, self.$refs.container)

    },
}
