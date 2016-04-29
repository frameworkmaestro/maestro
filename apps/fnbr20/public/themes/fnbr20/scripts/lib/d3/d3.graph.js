var D3Graph;
(function (j) {
    D3Graph = Class.extend({
        defaults: {},
        element: '',
        width: 0,
        height: 0,
        graph: null,
        type: {
            CX: {symbol: "circle", size: 200},
            SCHEMA: {symbol: "square", size: 200},
            common: {symbol: "circle", size: 80},
            ROLE: {symbol: "circle", size: 40},
            MEANING: {symbol: "circle", size: 40},
            top: {symbol: "triangle-up", size: 100},
            FR: {symbol: "square", size: 100},
            FE: {symbol: "circle", size: 80},
            lu: {symbol: "circle", size: 80},
            CE: {symbol: "circle", size: 80},
            cxn: {symbol: "square", size: 100},
            const: {symbol: "circle", size: 40},
            meaning: {symbol: "circle", size: 40},
            ONTOLOGY: {symbol: "triangle-up", size: 80}
        },
        container: null,
        test: 'defaultvalue',
        node: null,
        entities: [],
        entitiesn: [],
        highlightNodes: [],
        names: [],
        cxn: [],
        schema: [],
        ont: [],
        struct: {},
        spec: {nodes: [], links: []},
        index: 1000,
        currentEntity: null,
        relationData: [],
        relations: [
            'rel_common',
            'rel_subclass',
            'rel_fe-frame',
            'rel_frame-fe',
            'rel_fe-lu',
            'rel_lu-fe',
            'rel_fe-telic',
            'rel_fe-agentive',
            'rel_fe-constitutive',
            'rel_metaphor',
            'rel_type-of',
            'rel_frame-cxn',
            'rel_meaning-frame',
            'rel_const-cxn',
            'rel_cxn-meaning',
            'rel_cxn-const',
            'rel_evokes',
            'rel_elementof'
        ],

        vis: null,
        nodes: [],
        links: [],
        force: null,
        drag: null,

        // Initializing
        init: function (o) {
            this.element = o.element;
            this.index = 1000;
            this.spec = {nodes: [], links: []};
            this.setOptions(o);
            var $element = $('#' + this.element);
            this.width = $element.innerWidth() - 10;
            this.height = $element.innerHeight() - 10;
            this.vis = d3.select($element[0]).append("svg")
                .attr("width", this.width)
                .attr("height", this.height);
            this.force = d3.layout.force()
                .nodes(this.nodes)
                .links(this.links)
                //.gravity(.05)
                .size([this.width, this.height])
                .linkDistance(100)
                .charge(-300)
                .start();

            this.drag = this.force.drag()
                .on("dragstart", this.dragstart);

            // Per-type markers, as they don't inherit styles.
            this.vis.append("defs").selectAll("marker")
                .data(this.relations)
                .enter().append("marker")
                .attr("id", function (d) {
                    return d;
                })
                .attr("viewBox", "0 -5 10 10")
                .attr("refX", 18)
                .attr("refY", 0)
                .attr("markerWidth", 6)
                .attr("markerHeight", 6)
                .attr("orient", "auto")
                .append("path")
                .attr("class", function (d) {
                    return d;
                })
                .attr("d", "M0,-3L10,0L0,3");

            //this.update();
            this.clear();
        },

        clear: function () {
            this.clearLink();
            this.clearNode();
            //$('#' + this.element).html('');
        },

        loadNodes: function (struct) {
            var that = this;
            console.log(struct);
            if (struct.nodes) {
                $.each(struct.nodes, function (i, node) {
                    if (node.typeSystem != '') {
                        node.run = [];
                        that.addNode(node);
                    } else {
                        console.log(node);
                    }
                });
            }
            if (struct.links) {
                $.each(struct.links, function (i, link) {
                    l = {
                        sourceId: link.source,
                        targetId: link.target,
                        type: (link.label == "") ? 'rel_common' : link.label
                    };
                    that.addLinkById(l);
                });
            }
        },

        highlight: function (word) {
            var node = this.findNodeByType(word);
            if (node) {
                this.highlightNode(node);
            }
        },

        getNodes: function () {
            return this.nodes;
        },

        getLinks: function () {
            return this.links;
        },

        update: function () {
            var that = this;

            this.vis.selectAll("g").remove();

            var path = this.vis.append("g").selectAll("path")
                .data(this.force.links())
                .enter().append("line")
                .attr("class", function (d) {
                    return "link " + d.type;
                })
                .attr("marker-end", function (d) {
                    return (d.type == 'rel_elementof' ? "" : "url(#" + d.type + ")");
                })
                .on("mouseover", function (d) {
                    d3.select(this).attr("class", "link " + d.type + ' linkOver');
                })
                .on("mouseout", function (d) {
                    d3.select(this).attr("class", "link " + d.type);
                })
                .on("dblclick", this.clickLink);

            var node = this.vis.append("g").selectAll("path")
                .data(this.force.nodes())
                .enter().append('path')
                .attr("d", d3.svg.symbol()
                    .size(function (d) {
                        return d.size;
                    })
                    .type(function (d) {
                        return d.symbol;
                    })
                )
                .attr("class", function (d) {
                    var typeSystem = d.typeSystem ? d.typeSystem : 'common';
                    var cssClass = ($.inArray(d.id, that.highlightNodes) != -1) ? " nodeSelected" : ' entity_' + typeSystem;
                    return cssClass;
                })
                .on("dblclick", this.dblclick)
                .call(this.drag);

            var text = this.vis.append("g").selectAll("text")
                .data(this.force.nodes())
                .enter().append("text")
                .attr("x", 8)
                .attr("y", ".31em")
                .text(function (d) {
                    return d.name;
                });


            this.force.on("tick", function () {
                var transform = function (d) {
                    //console.log([w,h,d.x,d.y]);
                    //console.log(that.height + ' - ' + that.width);
                    //console.log(d);
                    d.x = Math.max(0, Math.min(that.width - 5, d.x));
                    d.y = Math.max(10, Math.min(that.height - 5, d.y));
                    return "translate(" + d.x + "," + d.y + ")";
                }

                path.attr("x1", function (d) {
                        return d.source.x;
                    })
                    .attr("y1", function (d) {
                        return d.source.y;
                    })
                    .attr("x2", function (d) {
                        return d.target.x;
                    })
                    .attr("y2", function (d) {
                        return d.target.y;
                    });
                node.attr("transform", transform);
                text.attr("transform", transform);
            });
            this.force.start();
        },

        addNode: function (node) {
            //console.log(node);
            var typeSystem = node.typeSystem ? node.typeSystem : 'common';
            node.size = this.type[typeSystem].size;
            node.symbol = this.type[typeSystem].symbol;
            this.nodes.push(node);
            this.update();
        },

        removeNode: function (id) {
            var i = 0;
            var n = this.findNode(id);
            while (i < this.links.length) {
                if ((this.links[i]['source'] === n) || (links[i]['target'] == n)) {
                    this.links.splice(i, 1);
                } else {
                    i++;
                }
            }
            var index = this.findNodeIndex(id);
            if (index !== undefined) {
                this.nodes.splice(index, 1);
                this.update();
            }
        },

        addLink: function (link) {
            var sourceNode = this.findNode(link.source.id);
            if (sourceNode === undefined) {
                this.addNode(link.source);
            } else {
                link.source = sourceNode;
            }
            var targetNode = this.findNode(link.target.id);
            if (targetNode === undefined) {
                this.addNode(link.target);
            } else {
                link.target = targetNode;
            }
            var existsLink = this.findLink(link);
            if (!existsLink) {
                this.links.push(link);
            }
            //console.log(links);
            this.update();
        },

        addLinkById: function (l) {
            var link = {};
            var sourceNode = this.findNode(l.sourceId);
            link.source = sourceNode;
            var targetNode = this.findNode(l.targetId);
            link.target = targetNode;
            link.type = l.type;
            var existsLink = this.findLink(link);
            if (!existsLink) {
                this.links.push(link);
            }
            //console.log(links);
            this.update();
        },

        refreshLink: function (types) {
            var i = 0;
            while (i < this.links.length) {
                if (types[links[i]['type']] === undefined) {
                    this.links.splice(i, 1);
                }
                else i++;
            }
            this.update();
        },

        clearLink: function () {
            var i = 0;
            while (i < this.links.length) {
                this.links.splice(i, 1);
            }
            //this.update();
        },

        clearNode: function () {
            var i = 0;
            while (i < this.nodes.length) {
                this.nodes[i].visited = false;
                this.nodes.splice(i, 1);
            }
            //this.update();
        },

        highlightNode: function (node) {
            this.highlightNodes.push(node.id);
            this.update();
        },

        resetHighLight: function () {
            this.highlightNodes = [];
            this.update();
        },

        findNode: function (id) {
            for (var i = 0; i < this.nodes.length; i++) {
                if (this.nodes[i].id === id) {
                    return this.nodes[i]
                }
            }
        },

        findNodeByType: function (type) {
            for (var i = 0; i < this.nodes.length; i++) {
                if (this.nodes[i].type === type) {
                    return this.nodes[i]
                }
            }
        },

        findNodeIndex: function (id) {
            for (var i = 0; i < this.nodes.length; i++) {
                if (this.nodes[i].id === id) {
                    return i
                }
            }
        },

        findLink: function (link) {
            for (var i = 0; i < this.links.length; i++) {
                var l = this.links[i];
                if ((l.source.id === link.source.id) && (l.target.id === link.target.id) && (l.type === link.type)) {
                    return true;
                }
            }
            return false;
        },

        dblclick: function () {
            this.onDblClick(d3.select(this).data()[0]);
        },

        clickLink: function () {
            this.clickLink(d3.select(this).data()[0]);
        },

        dragstart: function (d) {
            d3.select(this).classed("fixed", d.fixed = true);
        }

    });

    j.fn.D3Graph = function (o) {
        // initializing
        var args = arguments;
        var o = o || {'container': ''};
        return this.each(function () {
            // load the saved object
            var api = j.data(this, 'D3Graph');
            // create and save the object if it does not exist
            if (!api) {
                o.container = j(this);
                api = new D3Graph(o);
                j.data(this, 'D3Graph', api);
            }
            if (typeof api[o] == 'function') {
                if (args[0] == o) delete args[0];
                api[o].bind(api);
                var parameters = Array.prototype.slice.call(args, 1);
                return api[o].apply(api, parameters);
            }
            return api;
        });
    };
})(jQuery);