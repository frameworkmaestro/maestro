(function() {

    var sys = null
    var lastVisited = null

    Renderer = function(canvas) {
        canvas = $(canvas).get(0)
        var ctx = canvas.getContext("2d")
        var gfx = arbor.Graphics(canvas)
        var particleSystem = null
        var circle = null
        var loading = false
        var visitedNodes = []
        var ivisitedNodes = 0

        var checked = null
        var imgObjNode = new Image()
        imgObjNode.src = "/maestro/apps/fnbr/public/themes/fnbr/images/glyphicons_169_record.png"
        imgObjNode.onload = function() {
            imgObjNode.ready = true;
        };
        var imgObjVis = new Image()
        imgObjVis.src = "/maestro/apps/fnbr/public/themes/fnbr/images/glyphicons_169_record_b.png"
        imgObjVis.onload = function() {
            imgObjVis.ready = true;
        };
        var imgObjExp = new Image()
        imgObjExp.id = 'imgObjExp';
        imgObjExp.src = "/maestro/apps/fnbr/public/themes/fnbr/images/glyphicons_389_new_window_alt.png"
        imgObjExp.onload = function() {
            imgObjExp.ready = true;
        };
        var imgObjDet = new Image()
        imgObjDet.src = "/maestro/apps/fnbr/public/themes/fnbr/images/glyphicons_114_list.png"
        imgObjDet.onload = function() {
            imgObjDet.ready = true;
        };
        var imgObjCons = new Image()
        imgObjCons.src = "/maestro/apps/fnbr/public/themes/fnbr/images/glyphicons_155_show_big_thumbnails.png"
        imgObjCons.onload = function() {
            imgObjCons.ready = true;
        };
        var palette = {
            "1": "#FF0000", // heranca
            "2": "#0000FF", // subframe
            "3": "#006600", // uso
            "11": "#000000", // precedes
            "12": "#FFB6C1", // perspectiva
            "13": "#800080", // traducao
            "15": "#000000" // evokes
        }

        var that = {
            init: function(system) {
                particleSystem = system
                particleSystem.screen({padding: [60, 10, 10, 20], // leave some space at the bottom for the param sliders
                    step: .02}) // have the ‘camera’ zoom somewhat slowly as the graph unfolds 
                //$(window).resize(that.resize)
                that.resize()

                that.initMouseHandling()
            },
            redraw: function() {
                if (loading)
                    return

                if (particleSystem === null)
                    return

                ctx.clearRect(0, 0, canvas.width, canvas.height)
                ctx.strokeStyle = "#d3d3d3"
                ctx.lineWidth = 1
                ctx.beginPath()
                ctx.stroke()

                //gfx.clear() // convenience ƒ: clears the whole canvas rect

                sys.eachEdge(function(edge, p1, p2){
                    gfx.line(p1, p2, {stroke:palette[edge.data.type], width:1})
                })


                // draw the nodes & save their bounds for edge drawing
                var nodeBoxes = {}

                particleSystem.eachNode(function(node, pt) {
                    // node: {mass:#, p:{x,y}, name:"", data:{}}
                    // pt:   {x:#, y:#}  node position in screen coords

                    // draw a rectangle centered at pt
                    //if (node.data.color)
                    //console.log(checked)
                    if (checked == node)
                        ctx.fillStyle = "black" //node.data.color
                    else
                        ctx.fillStyle = "#d3d3d3" //"white" //ctx.fillStyle = "rgba(0,0,0,.2)"
                    //if (node.data.color == 'none')
                    //    ctx.fillStyle = "white"

                    w = 15
                    //if (node.data.shape == 'dot') {
                    //          gfx.oval(pt.x - w / 2, pt.y - w / 2, w, w, {fill: ctx.fillStyle})
                    if (node.data.type == 'C') { 
                        if (imgObjCons.ready) {
                            ctx.drawImage(imgObjCons, pt.x - 10, pt.y - 10, 20, 20)
                        }
                    } else {
                        if (visitedNodes.indexOf(node.name) != -1) { // if (node.data.visited) {
                            if (imgObjVis.ready) {
                                ctx.drawImage(imgObjVis, pt.x - 10, pt.y - 10, 20, 20)
                            }
                        } else {
                            if (imgObjNode.ready) {
                                ctx.drawImage(imgObjNode, pt.x - 10, pt.y - 10, 20, 20)
                            }
                        }
                        if (checked == node) {
                            if (visitedNodes.indexOf(node.name) == -1) {
                                ctx.drawImage(imgObjExp, pt.x - 8, pt.y - 28, 15, 15)
                            }
                            ctx.drawImage(imgObjDet, pt.x - 8, pt.y + 13, 15, 15)
                        }
                    }
                    nodeBoxes[node.name] = [pt.x - w / 2, pt.y - w / 2, w, w]
                    //} else {
                    //    gfx.rect(pt.x - w / 2, pt.y - 10, w, 20, 4, {fill: ctx.fillStyle})
                    //    nodeBoxes[node.name] = [pt.x - w / 2, pt.y - 11, w, 22]
                    //}
                })

                /*
                // draw the edges
                particleSystem.eachEdge(function(edge, pt1, pt2) {
                    // edge: {source:Node, target:Node, length:#, data:{}}
                    // pt1:  {x:#, y:#}  source position in screen coords
                    // pt2:  {x:#, y:#}  target position in screen coords

                    var weight = 1.6 //edge.data.weight
                    var color = edge.data.color

                    if (!color || ("" + color).match(/^[ \t]*$/))
                        color = null

                    // find the start point
                    var tail = intersect_line_box(pt1, pt2, nodeBoxes[edge.source.name])
                    var head = intersect_line_box(tail, pt2, nodeBoxes[edge.target.name])

                    ctx.save()
                    ctx.beginPath()
                    ctx.lineWidth = (!isNaN(weight)) ? parseFloat(weight) : 1
                    ctx.strokeStyle = palette[edge.data.type];//(color) ? color : "#cccccc"
                    ctx.fillStyle = null

                    ctx.moveTo(tail.x, tail.y)
                    ctx.lineTo(head.x, head.y)
                    ctx.stroke()
                    ctx.restore()

                    // draw an arrowhead if this is a -> style edge
                    //if (edge.data.directed){
                    ctx.save()
                    // move to the head position of the edge we just drew
                    var wt = !isNaN(weight) ? parseFloat(weight) : 1
                    var arrowLength = 6 + wt
                    var arrowWidth = 2 + wt
                    ctx.fillStyle = palette[edge.data.type];//(color) ? color : "#cccccc"
                    ctx.translate(head.x, head.y);
                    ctx.rotate(Math.atan2(head.y - tail.y, head.x - tail.x));

                    // delete some of the edge that's already there (so the point isn't hidden)
                    ctx.clearRect(-arrowLength / 2, -wt / 2, arrowLength / 2, wt)

                    // draw the chevron
                    ctx.beginPath();
                    ctx.moveTo(-arrowLength, arrowWidth);
                    ctx.lineTo(0, 0);
                    ctx.lineTo(-arrowLength, -arrowWidth);
                    ctx.lineTo(-arrowLength * 0.8, -0);
                    ctx.closePath();
                    ctx.fill();
                    ctx.restore()
                    //}
                })
                */

                particleSystem.eachNode(function(node, pt) {
                    // determine the box size and round off the coords if we'll be 
                    // drawing a text label (awful alignment jitter otherwise...)
                    var label = node.data.label || ""
                    var w = ctx.measureText("" + label).width
                    //if (!("" + label).match(/^[ \t]*$/)) {
                    pt.x = Math.floor(pt.x) + (w / 2) + 20
                    pt.y = Math.floor(pt.y) + 2
                    //} else {
                    //    label = null
                    //}
                    // draw the text
                    if (label) {
                        ctx.fillStyle = "white" // #CCCCCC"
                        gfx.rect(pt.x - w / 2, pt.y - 11, w, 13, 4, {fill: ctx.fillStyle})
                        ctx.font = "12px Helvetica"
                        ctx.textAlign = "center"
                        ctx.fillStyle = "black" //#3388CC"
                        ctx.fillText(label || "", pt.x, pt.y)
                        ctx.fillText(label || "", pt.x, pt.y)
                    }

                })


            },
            resize: function() {
        /*
                var w = $(window).width(),
                        h = $(window).height();
                if (w > 800) {
                    w = 800;
                }
                if (h > 600) {
                    h = 600;
                }
         */
                var w = canvas.width,
                    h = canvas.height;
                console.log('w=' + w);
                console.log('h=' + h);
                canvas.width = w;
                canvas.height = h // resize the canvas element to fill the screen
                particleSystem.screenSize(w, h) // inform the system so it can map coords for us
                that.redraw();
            },
            initMouseHandling: function() {
                // no-nonsense drag and drop (thanks springy.js)
                selected = null;
                nearest = null;
                var dragged = null;
                var oldmass = 1

                $(canvas).mousedown(function(e) {
                    if (loading)
                        return
                    var pos = $(this).offset();
                    var p = {x: e.pageX - pos.left, y: e.pageY - pos.top}
                    selected = nearest = dragged = particleSystem.nearest(p);

                    if (selected.node !== null) {
                        // dragged.node.tempMass = 10000
                        dragged.node.fixed = true
                    }
                    return false
                });

                $(canvas).mousemove(function(e) {
                    if (loading)
                        return
                    var old_nearest = nearest && nearest.node._id
                    var pos = $(this).offset();
                    var s = {x: e.pageX - pos.left, y: e.pageY - pos.top};

                    nearest = particleSystem.nearest(s);
                    if (!nearest)
                        return

                    if (dragged !== null && dragged.node !== null) {
                        var p = particleSystem.fromScreen(s)
                        dragged.node.p = {x: p.x, y: p.y}
                        // dragged.tempMass = 10000
                    }

                    return false
                });

                $(canvas).click(function(e) {
                    if (loading)
                        return
                    var old_nearest = nearest && nearest.node._id
                    var pos = $(this).offset();
                    var s = {x: e.pageX - pos.left, y: e.pageY - pos.top};

                    nearest = particleSystem.nearest(s);
                    if (!nearest)
                        return

                    node = nearest.node;
                    id = node.data.id;

                    pt = sys.toScreen(node.p)
                    pt.x = Math.floor(pt.x)
                    pt.y = Math.floor(pt.y)

                    console.log(s);
                    console.log(pt)

                    exp = det = false
                    if (checked != null) {
                        if ((s.x > (pt.x - 10)) && (s.x < (pt.x + 10))) {
                            if ((s.y > (pt.y - 28)) && (s.y < (pt.y - 13))) {
                                exp = true
                            }
                            if ((s.y > (pt.y + 13)) && (s.y < (pt.y + 32))) {
                                det = true
                            }
                        }
                    }

                    if (exp) {
                        visitedNodes[ivisitedNodes++] = node.name
                        loading = true
                        console.log('loading...');
                        circle = new Sonic({
                            width: 50,
                            height: 50,
                            padding: 0,
                            x: pt.x - 26,
                            y: pt.y - 26,
                            strokeColor: '#000',
                            backgroundColor: "rgba(0,0,0,0)",
                            pointDistance: .01,
                            stepsPerFrame: 3,
                            trailLength: .7,
                            step: 'fader',
                            setup: function() {
                                this._ = ctx;
                                this._.lineWidth = 5;
                            },
                            path: [
                                ['arc', pt.x, pt.y, 20, 0, 360]
                            ]
                        });
                        circle.play();

                        lastVisited = id
                        console.log('lastvisited = ' + lastVisited)
                        
                        //checked = null
                        $.getJSON("/maestro/index.php/fnbr/fnbr20/arbor/" + id + queryString, function(data) {
                            // load the raw data into the particle system as is (since it's already formatted correctly for .merge)
                            var nodes = data.nodes
                            //console.log(nodes);
                            particleSystem.graft({nodes: nodes, edges: data.edges})
                            circle.stop();
                            loading = false
                        })
                        console.log('loaded...');
                        checked = null
                    } else if (det) {
                        //checked = null
                        var url = "http://appta.com.br/projetos/framenet/frame/" + id + "/" + lang;
                        $(location).attr('target', '_parent');
                        $(location).attr('href', url);
                    } else {
                        checked = node
                    }

                    return false
                });

                $(window).bind('mouseup', function(e) {
                    if (loading)
                        return
                    if (dragged === null || dragged.node === undefined)
                        return
                    dragged.node.fixed = false
                    dragged.node.tempMass = 100
                    dragged = null;
                    selected = null
                    return false
                });

            }
        }

        // helpers for figuring out where to draw arrows (thanks springy.js)
        var intersect_line_line = function(p1, p2, p3, p4)
        {
            var denom = ((p4.y - p3.y) * (p2.x - p1.x) - (p4.x - p3.x) * (p2.y - p1.y));
            if (denom === 0)
                return false // lines are parallel
            var ua = ((p4.x - p3.x) * (p1.y - p3.y) - (p4.y - p3.y) * (p1.x - p3.x)) / denom;
            var ub = ((p2.x - p1.x) * (p1.y - p3.y) - (p2.y - p1.y) * (p1.x - p3.x)) / denom;

            if (ua < 0 || ua > 1 || ub < 0 || ub > 1)
                return false
            return arbor.Point(p1.x + ua * (p2.x - p1.x), p1.y + ua * (p2.y - p1.y));
        }

        var intersect_line_box = function(p1, p2, boxTuple)
        {
            var p3 = {x: boxTuple[0], y: boxTuple[1]},
            w = boxTuple[2],
                    h = boxTuple[3]

            var tl = {x: p3.x, y: p3.y};
            var tr = {x: p3.x + w, y: p3.y};
            var bl = {x: p3.x, y: p3.y + h};
            var br = {x: p3.x + w, y: p3.y + h};

            return intersect_line_line(p1, p2, tl, tr) ||
                    intersect_line_line(p1, p2, tr, br) ||
                    intersect_line_line(p1, p2, br, bl) ||
                    intersect_line_line(p1, p2, bl, tl) ||
                    false
        }

        return that
    }

    var Net = function() {
        sys = arbor.ParticleSystem(4000, 400, 0.5, 55)
        sys.renderer = Renderer("#viewport"); // our newly created renderer will have its .init() method called shortly by sys...
        console.log("/maestro/index.php/fnbr/fnbr20/arbor/" + startFrame + queryString);
        $.getJSON("/maestro/index.php/fnbr/fnbr20/arbor/" + startFrame + queryString, function(data) {
            // load the raw data into the particle system as is (since it's already formatted correctly for .merge)
            var nodes = data.nodes
            sys.graft({nodes: nodes, edges: data.edges})
        })
        $.getJSON("/maestro/apps/fnbr20/public/data/frames.json", function(data) {
            var nodes = data.nodes
            sys.graft({nodes: nodes, edges: data.edges})
        })
        $.getJSON("/maestro/apps/fnbr20/public/data/constructions.json", function(data) {
            var nodes = data.nodes
            sys.graft({nodes: nodes, edges: data.edges})
        })
        $.getJSON("/maestro/apps/fnbr20/public/data/cnxframe.json", function(data) {
            var nodes = data.nodes
            sys.graft({nodes: nodes, edges: data.edges})
        })
    }
    
    var mcp = Net();

})()