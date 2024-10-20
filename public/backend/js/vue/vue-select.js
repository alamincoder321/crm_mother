!(function (t, e) {
    "object" == typeof exports && "object" == typeof module
        ? (module.exports = e())
        : "function" == typeof define && define.amd
        ? define([], e)
        : "object" == typeof exports
        ? (exports.VueSelect = e())
        : (t.VueSelect = e());
})(this, function () {
    return (function (t) {
        function e(o) {
            if (n[o]) return n[o].exports;
            var r = (n[o] = { exports: {}, id: o, loaded: !1 });
            return (
                t[o].call(r.exports, r, r.exports, e),
                (r.loaded = !0),
                r.exports
            );
        }
        var n = {};
        return (e.m = t), (e.c = n), (e.p = "/"), e(0);
    })([
        function (t, e, n) {
            "use strict";
            function o(t) {
                return t && t.__esModule ? t : { default: t };
            }
            Object.defineProperty(e, "__esModule", { value: !0 }),
                (e.mixins = e.VueSelect = void 0);
            var r = n(85),
                i = o(r),
                s = n(42),
                a = o(s);
            (e.default = i.default),
                (e.VueSelect = i.default),
                (e.mixins = a.default);
        },
        function (t, e) {
            var n = (t.exports =
                "undefined" != typeof window && window.Math == Math
                    ? window
                    : "undefined" != typeof self && self.Math == Math
                    ? self
                    : Function("return this")());
            "number" == typeof __g && (__g = n);
        },
        function (t, e) {
            var n = (t.exports = { version: "2.5.3" });
            "number" == typeof __e && (__e = n);
        },
        function (t, e, n) {
            t.exports = !n(9)(function () {
                return (
                    7 !=
                    Object.defineProperty({}, "a", {
                        get: function () {
                            return 7;
                        },
                    }).a
                );
            });
        },
        function (t, e) {
            var n = {}.hasOwnProperty;
            t.exports = function (t, e) {
                return n.call(t, e);
            };
        },
        function (t, e, n) {
            var o = n(11),
                r = n(33),
                i = n(25),
                s = Object.defineProperty;
            e.f = n(3)
                ? Object.defineProperty
                : function (t, e, n) {
                      if ((o(t), (e = i(e, !0)), o(n), r))
                          try {
                              return s(t, e, n);
                          } catch (t) {}
                      if ("get" in n || "set" in n)
                          throw TypeError("Accessors not supported!");
                      return "value" in n && (t[e] = n.value), t;
                  };
        },
        function (t, e, n) {
            var o = n(5),
                r = n(14);
            t.exports = n(3)
                ? function (t, e, n) {
                      return o.f(t, e, r(1, n));
                  }
                : function (t, e, n) {
                      return (t[e] = n), t;
                  };
        },
        function (t, e, n) {
            var o = n(61),
                r = n(16);
            t.exports = function (t) {
                return o(r(t));
            };
        },
        function (t, e, n) {
            var o = n(23)("wks"),
                r = n(15),
                i = n(1).Symbol,
                s = "function" == typeof i,
                a = (t.exports = function (t) {
                    return (
                        o[t] ||
                        (o[t] = (s && i[t]) || (s ? i : r)("Symbol." + t))
                    );
                });
            a.store = o;
        },
        function (t, e) {
            t.exports = function (t) {
                try {
                    return !!t();
                } catch (t) {
                    return !0;
                }
            };
        },
        function (t, e) {
            t.exports = function (t) {
                return "object" == typeof t
                    ? null !== t
                    : "function" == typeof t;
            };
        },
        function (t, e, n) {
            var o = n(10);
            t.exports = function (t) {
                if (!o(t)) throw TypeError(t + " is not an object!");
                return t;
            };
        },
        function (t, e, n) {
            var o = n(1),
                r = n(2),
                i = n(58),
                s = n(6),
                a = "prototype",
                u = function (t, e, n) {
                    var l,
                        c,
                        f,
                        p = t & u.F,
                        d = t & u.G,
                        h = t & u.S,
                        b = t & u.P,
                        v = t & u.B,
                        g = t & u.W,
                        y = d ? r : r[e] || (r[e] = {}),
                        m = y[a],
                        x = d ? o : h ? o[e] : (o[e] || {})[a];
                    d && (n = e);
                    for (l in n)
                        (c = !p && x && void 0 !== x[l]),
                            (c && l in y) ||
                                ((f = c ? x[l] : n[l]),
                                (y[l] =
                                    d && "function" != typeof x[l]
                                        ? n[l]
                                        : v && c
                                        ? i(f, o)
                                        : g && x[l] == f
                                        ? (function (t) {
                                              var e = function (e, n, o) {
                                                  if (this instanceof t) {
                                                      switch (
                                                          arguments.length
                                                      ) {
                                                          case 0:
                                                              return new t();
                                                          case 1:
                                                              return new t(e);
                                                          case 2:
                                                              return new t(
                                                                  e,
                                                                  n
                                                              );
                                                      }
                                                      return new t(e, n, o);
                                                  }
                                                  return t.apply(
                                                      this,
                                                      arguments
                                                  );
                                              };
                                              return (e[a] = t[a]), e;
                                          })(f)
                                        : b && "function" == typeof f
                                        ? i(Function.call, f)
                                        : f),
                                b &&
                                    (((y.virtual || (y.virtual = {}))[l] = f),
                                    t & u.R && m && !m[l] && s(m, l, f)));
                };
            (u.F = 1),
                (u.G = 2),
                (u.S = 4),
                (u.P = 8),
                (u.B = 16),
                (u.W = 32),
                (u.U = 64),
                (u.R = 128),
                (t.exports = u);
        },
        function (t, e, n) {
            var o = n(38),
                r = n(17);
            t.exports =
                Object.keys ||
                function (t) {
                    return o(t, r);
                };
        },
        function (t, e) {
            t.exports = function (t, e) {
                return {
                    enumerable: !(1 & t),
                    configurable: !(2 & t),
                    writable: !(4 & t),
                    value: e,
                };
            };
        },
        function (t, e) {
            var n = 0,
                o = Math.random();
            t.exports = function (t) {
                return "Symbol(".concat(
                    void 0 === t ? "" : t,
                    ")_",
                    (++n + o).toString(36)
                );
            };
        },
        function (t, e) {
            t.exports = function (t) {
                if (void 0 == t) throw TypeError("Can't call method on  " + t);
                return t;
            };
        },
        function (t, e) {
            t.exports =
                "constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf".split(
                    ","
                );
        },
        function (t, e) {
            t.exports = {};
        },
        function (t, e) {
            t.exports = !0;
        },
        function (t, e) {
            e.f = {}.propertyIsEnumerable;
        },
        function (t, e, n) {
            var o = n(5).f,
                r = n(4),
                i = n(8)("toStringTag");
            t.exports = function (t, e, n) {
                t &&
                    !r((t = n ? t : t.prototype), i) &&
                    o(t, i, { configurable: !0, value: e });
            };
        },
        function (t, e, n) {
            var o = n(23)("keys"),
                r = n(15);
            t.exports = function (t) {
                return o[t] || (o[t] = r(t));
            };
        },
        function (t, e, n) {
            var o = n(1),
                r = "__core-js_shared__",
                i = o[r] || (o[r] = {});
            t.exports = function (t) {
                return i[t] || (i[t] = {});
            };
        },
        function (t, e) {
            var n = Math.ceil,
                o = Math.floor;
            t.exports = function (t) {
                return isNaN((t = +t)) ? 0 : (t > 0 ? o : n)(t);
            };
        },
        function (t, e, n) {
            var o = n(10);
            t.exports = function (t, e) {
                if (!o(t)) return t;
                var n, r;
                if (
                    e &&
                    "function" == typeof (n = t.toString) &&
                    !o((r = n.call(t)))
                )
                    return r;
                if ("function" == typeof (n = t.valueOf) && !o((r = n.call(t))))
                    return r;
                if (
                    !e &&
                    "function" == typeof (n = t.toString) &&
                    !o((r = n.call(t)))
                )
                    return r;
                throw TypeError("Can't convert object to primitive value");
            };
        },
        function (t, e, n) {
            var o = n(1),
                r = n(2),
                i = n(19),
                s = n(27),
                a = n(5).f;
            t.exports = function (t) {
                var e = r.Symbol || (r.Symbol = i ? {} : o.Symbol || {});
                "_" == t.charAt(0) || t in e || a(e, t, { value: s.f(t) });
            };
        },
        function (t, e, n) {
            e.f = n(8);
        },
        function (t, e) {
            "use strict";
            t.exports = {
                props: {
                    loading: { type: Boolean, default: !1 },
                    onSearch: { type: Function, default: function (t, e) {} },
                },
                data: function () {
                    return { mutableLoading: !1 };
                },
                watch: {
                    search: function () {
                        this.search.length > 0 &&
                            (this.onSearch(this.search, this.toggleLoading),
                            this.$emit(
                                "search",
                                this.search,
                                this.toggleLoading
                            ));
                    },
                    loading: function (t) {
                        this.mutableLoading = t;
                    },
                },
                methods: {
                    toggleLoading: function () {
                        var t =
                            arguments.length > 0 && void 0 !== arguments[0]
                                ? arguments[0]
                                : null;
                        return null == t
                            ? (this.mutableLoading = !this.mutableLoading)
                            : (this.mutableLoading = t);
                    },
                },
            };
        },
        function (t, e) {
            "use strict";
            t.exports = {
                watch: {
                    typeAheadPointer: function () {
                        this.maybeAdjustScroll();
                    },
                },
                methods: {
                    maybeAdjustScroll: function () {
                        var t = this.pixelsToPointerTop(),
                            e = this.pixelsToPointerBottom();
                        return t <= this.viewport().top
                            ? this.scrollTo(t)
                            : e >= this.viewport().bottom
                            ? this.scrollTo(
                                  this.viewport().top + this.pointerHeight()
                              )
                            : void 0;
                    },
                    pixelsToPointerTop: function t() {
                        var t = 0;
                        if (this.$refs.dropdownMenu)
                            for (var e = 0; e < this.typeAheadPointer; e++)
                                t +=
                                    this.$refs.dropdownMenu.children[e]
                                        .offsetHeight;
                        return t;
                    },
                    pixelsToPointerBottom: function () {
                        return this.pixelsToPointerTop() + this.pointerHeight();
                    },
                    pointerHeight: function () {
                        var t =
                            !!this.$refs.dropdownMenu &&
                            this.$refs.dropdownMenu.children[
                                this.typeAheadPointer
                            ];
                        return t ? t.offsetHeight : 0;
                    },
                    viewport: function () {
                        return {
                            top: this.$refs.dropdownMenu
                                ? this.$refs.dropdownMenu.scrollTop
                                : 0,
                            bottom: this.$refs.dropdownMenu
                                ? this.$refs.dropdownMenu.offsetHeight +
                                  this.$refs.dropdownMenu.scrollTop
                                : 0,
                        };
                    },
                    scrollTo: function (t) {
                        return this.$refs.dropdownMenu
                            ? (this.$refs.dropdownMenu.scrollTop = t)
                            : null;
                    },
                },
            };
        },
        function (t, e) {
            "use strict";
            t.exports = {
                data: function () {
                    return { typeAheadPointer: -1 };
                },
                watch: {
                    filteredOptions: function () {
                        this.typeAheadPointer = 0;
                    },
                },
                methods: {
                    typeAheadUp: function () {
                        this.typeAheadPointer > 0 &&
                            (this.typeAheadPointer--,
                            this.maybeAdjustScroll && this.maybeAdjustScroll());
                    },
                    typeAheadDown: function () {
                        this.typeAheadPointer <
                            this.filteredOptions.length - 1 &&
                            (this.typeAheadPointer++,
                            this.maybeAdjustScroll && this.maybeAdjustScroll());
                    },
                    typeAheadSelect: function () {
                        this.filteredOptions[this.typeAheadPointer]
                            ? this.select(
                                  this.filteredOptions[this.typeAheadPointer]
                              )
                            : this.taggable &&
                              this.search.length &&
                              this.select(this.search),
                            this.clearSearchOnSelect && (this.search = "");
                    },
                },
            };
        },
        function (t, e) {
            var n = {}.toString;
            t.exports = function (t) {
                return n.call(t).slice(8, -1);
            };
        },
        function (t, e, n) {
            var o = n(10),
                r = n(1).document,
                i = o(r) && o(r.createElement);
            t.exports = function (t) {
                return i ? r.createElement(t) : {};
            };
        },
        function (t, e, n) {
            t.exports =
                !n(3) &&
                !n(9)(function () {
                    return (
                        7 !=
                        Object.defineProperty(n(32)("div"), "a", {
                            get: function () {
                                return 7;
                            },
                        }).a
                    );
                });
        },
        function (t, e, n) {
            "use strict";
            var o = n(19),
                r = n(12),
                i = n(39),
                s = n(6),
                a = n(4),
                u = n(18),
                l = n(63),
                c = n(21),
                f = n(69),
                p = n(8)("iterator"),
                d = !([].keys && "next" in [].keys()),
                h = "@@iterator",
                b = "keys",
                v = "values",
                g = function () {
                    return this;
                };
            t.exports = function (t, e, n, y, m, x, w) {
                l(n, e, y);
                var S,
                    O,
                    _,
                    j = function (t) {
                        if (!d && t in C) return C[t];
                        switch (t) {
                            case b:
                                return function () {
                                    return new n(this, t);
                                };
                            case v:
                                return function () {
                                    return new n(this, t);
                                };
                        }
                        return function () {
                            return new n(this, t);
                        };
                    },
                    k = e + " Iterator",
                    P = m == v,
                    A = !1,
                    C = t.prototype,
                    M = C[p] || C[h] || (m && C[m]),
                    L = (!d && M) || j(m),
                    T = m ? (P ? j("entries") : L) : void 0,
                    E = "Array" == e ? C.entries || M : M;
                if (
                    (E &&
                        ((_ = f(E.call(new t()))),
                        _ !== Object.prototype &&
                            _.next &&
                            (c(_, k, !0), o || a(_, p) || s(_, p, g))),
                    P &&
                        M &&
                        M.name !== v &&
                        ((A = !0),
                        (L = function () {
                            return M.call(this);
                        })),
                    (o && !w) || (!d && !A && C[p]) || s(C, p, L),
                    (u[e] = L),
                    (u[k] = g),
                    m)
                )
                    if (
                        ((S = {
                            values: P ? L : j(v),
                            keys: x ? L : j(b),
                            entries: T,
                        }),
                        w)
                    )
                        for (O in S) O in C || i(C, O, S[O]);
                    else r(r.P + r.F * (d || A), e, S);
                return S;
            };
        },
        function (t, e, n) {
            var o = n(11),
                r = n(66),
                i = n(17),
                s = n(22)("IE_PROTO"),
                a = function () {},
                u = "prototype",
                l = function () {
                    var t,
                        e = n(32)("iframe"),
                        o = i.length,
                        r = "<",
                        s = ">";
                    for (
                        e.style.display = "none",
                            n(60).appendChild(e),
                            e.src = "javascript:",
                            t = e.contentWindow.document,
                            t.open(),
                            t.write(
                                r +
                                    "script" +
                                    s +
                                    "document.F=Object" +
                                    r +
                                    "/script" +
                                    s
                            ),
                            t.close(),
                            l = t.F;
                        o--;

                    )
                        delete l[u][i[o]];
                    return l();
                };
            t.exports =
                Object.create ||
                function (t, e) {
                    var n;
                    return (
                        null !== t
                            ? ((a[u] = o(t)),
                              (n = new a()),
                              (a[u] = null),
                              (n[s] = t))
                            : (n = l()),
                        void 0 === e ? n : r(n, e)
                    );
                };
        },
        function (t, e, n) {
            var o = n(38),
                r = n(17).concat("length", "prototype");
            e.f =
                Object.getOwnPropertyNames ||
                function (t) {
                    return o(t, r);
                };
        },
        function (t, e) {
            e.f = Object.getOwnPropertySymbols;
        },
        function (t, e, n) {
            var o = n(4),
                r = n(7),
                i = n(57)(!1),
                s = n(22)("IE_PROTO");
            t.exports = function (t, e) {
                var n,
                    a = r(t),
                    u = 0,
                    l = [];
                for (n in a) n != s && o(a, n) && l.push(n);
                for (; e.length > u; )
                    o(a, (n = e[u++])) && (~i(l, n) || l.push(n));
                return l;
            };
        },
        function (t, e, n) {
            t.exports = n(6);
        },
        function (t, e, n) {
            var o = n(16);
            t.exports = function (t) {
                return Object(o(t));
            };
        },
        function (t, e, n) {
            "use strict";
            function o(t) {
                return t && t.__esModule ? t : { default: t };
            }
            Object.defineProperty(e, "__esModule", { value: !0 });
            var r = n(45),
                i = o(r),
                s = n(48),
                a = o(s),
                u = n(43),
                l = o(u),
                c = n(49),
                f = o(c),
                p = n(29),
                d = o(p),
                h = n(30),
                b = o(h),
                v = n(28),
                g = o(v);
            e.default = {
                mixins: [d.default, b.default, g.default],
                props: {
                    value: { default: null },
                    options: {
                        type: Array,
                        default: function () {
                            return [];
                        },
                    },
                    disabled: { type: Boolean, default: !1 },
                    clearable: { type: Boolean, default: !0 },
                    maxHeight: { type: String, default: "400px" },
                    searchable: { type: Boolean, default: !0 },
                    multiple: { type: Boolean, default: !1 },
                    placeholder: { type: String, default: "" },
                    transition: { type: String, default: "fade" },
                    clearSearchOnSelect: { type: Boolean, default: !0 },
                    closeOnSelect: { type: Boolean, default: !0 },
                    label: { type: String, default: "label" },
                    index: { type: String, default: null },
                    getOptionLabel: {
                        type: Function,
                        default: function (t) {
                            return (
                                this.index &&
                                    (t = this.findOptionByIndexValue(t)),
                                "object" ===
                                ("undefined" == typeof t
                                    ? "undefined"
                                    : (0, f.default)(t))
                                    ? t.hasOwnProperty(this.label)
                                        ? t[this.label]
                                        : console.warn(
                                              '[vue-select warn]: Label key "option.' +
                                                  this.label +
                                                  '" does not' +
                                                  (" exist in options object " +
                                                      (0, l.default)(t) +
                                                      ".\n") +
                                                  "http://sagalbot.github.io/vue-select/#ex-labels"
                                          )
                                    : t
                            );
                        },
                    },
                    onChange: {
                        type: Function,
                        default: function (t) {
                            this.$emit("input", t);
                        },
                    },
                    onTab: {
                        type: Function,
                        default: function () {
                            this.selectOnTab && this.typeAheadSelect();
                        },
                    },
                    taggable: { type: Boolean, default: !1 },
                    tabindex: { type: Number, default: null },
                    pushTags: { type: Boolean, default: !1 },
                    filterable: { type: Boolean, default: !0 },
                    filterBy: {
                        type: Function,
                        default: function (t, e, n) {
                            return (
                                (e || "")
                                    .toLowerCase()
                                    .indexOf(n.toLowerCase()) > -1
                            );
                        },
                    },
                    filter: {
                        type: Function,
                        default: function (t, e) {
                            var n = this;
                            return t.filter(function (t) {
                                var o = n.getOptionLabel(t);
                                return (
                                    "number" == typeof o && (o = o.toString()),
                                    n.filterBy(t, o, e)
                                );
                            });
                        },
                    },
                    createOption: {
                        type: Function,
                        default: function (t) {
                            return (
                                "object" ===
                                    (0, f.default)(this.mutableOptions[0]) &&
                                    (t = (0, a.default)({}, this.label, t)),
                                this.$emit("option:created", t),
                                t
                            );
                        },
                    },
                    resetOnOptionsChange: { type: Boolean, default: !1 },
                    noDrop: { type: Boolean, default: !1 },
                    inputId: { type: String },
                    dir: { type: String, default: "auto" },
                    selectOnTab: { type: Boolean, default: !1 },
                },
                data: function () {
                    return {
                        search: "",
                        open: !1,
                        mutableValue: null,
                        mutableOptions: [],
                    };
                },
                watch: {
                    value: function (t) {
                        this.mutableValue = t;
                    },
                    mutableValue: function (t, e) {
                        this.multiple
                            ? this.onChange
                                ? this.onChange(t)
                                : null
                            : this.onChange && t !== e
                            ? this.onChange(t)
                            : null;
                    },
                    options: function (t) {
                        this.mutableOptions = t;
                    },
                    mutableOptions: function () {
                        !this.taggable &&
                            this.resetOnOptionsChange &&
                            (this.mutableValue = this.multiple ? [] : null);
                    },
                    multiple: function (t) {
                        this.mutableValue = t ? [] : null;
                    },
                },
                created: function () {
                    (this.mutableValue = this.value),
                        (this.mutableOptions = this.options.slice(0)),
                        (this.mutableLoading = this.loading),
                        this.$on("option:created", this.maybePushTag);
                },
                methods: {
                    select: function (t) {
                        if (!this.isOptionSelected(t)) {
                            if (
                                (this.taggable &&
                                    !this.optionExists(t) &&
                                    (t = this.createOption(t)),
                                this.index)
                            ) {
                                if (!t.hasOwnProperty(this.index))
                                    return console.warn(
                                        '[vue-select warn]: Index key "option.' +
                                            this.index +
                                            '" does not' +
                                            (" exist in options object " +
                                                (0, l.default)(t) +
                                                ".")
                                    );
                                t = t[this.index];
                            }
                            this.multiple && !this.mutableValue
                                ? (this.mutableValue = [t])
                                : this.multiple
                                ? this.mutableValue.push(t)
                                : (this.mutableValue = t);
                        }
                        this.onAfterSelect(t);
                    },
                    deselect: function (t) {
                        var e = this;
                        if (this.multiple) {
                            var n = -1;
                            this.mutableValue.forEach(function (o) {
                                (o === t ||
                                    (e.index && o === t[e.index]) ||
                                    ("object" ===
                                        ("undefined" == typeof o
                                            ? "undefined"
                                            : (0, f.default)(o)) &&
                                        o[e.label] === t[e.label])) &&
                                    (n = o);
                            });
                            var o = this.mutableValue.indexOf(n);
                            this.mutableValue.splice(o, 1);
                        } else this.mutableValue = null;
                    },
                    clearSelection: function () {
                        this.mutableValue = this.multiple ? [] : null;
                    },
                    onAfterSelect: function (t) {
                        this.closeOnSelect &&
                            ((this.open = !this.open),
                            this.$refs.search.blur()),
                            this.clearSearchOnSelect && (this.search = "");
                    },
                    toggleDropdown: function (t) {
                        (t.target === this.$refs.openIndicator ||
                            t.target === this.$refs.search ||
                            t.target === this.$refs.toggle ||
                            t.target.classList.contains("selected-tag") ||
                            t.target === this.$el) &&
                            (this.open
                                ? this.$refs.search.blur()
                                : this.disabled ||
                                  ((this.open = !0),
                                  this.$refs.search.focus()));
                    },
                    isOptionSelected: function (t) {
                        var e = this,
                            n = !1;
                        return (
                            this.valueAsArray.forEach(function (o) {
                                "object" ===
                                ("undefined" == typeof o
                                    ? "undefined"
                                    : (0, f.default)(o))
                                    ? (n = e.optionObjectComparator(o, t))
                                    : (o !== t && o !== t[e.index]) || (n = !0);
                            }),
                            n
                        );
                    },
                    optionObjectComparator: function (t, e) {
                        return (
                            !(!this.index || t !== e[this.index]) ||
                            t[this.label] === e[this.label] ||
                            t[this.label] === e ||
                            !(!this.index || t[this.index] !== e[this.index])
                        );
                    },
                    findOptionByIndexValue: function (t) {
                        var e = this;
                        return (
                            this.options.forEach(function (n) {
                                (0, l.default)(n[e.index]) ===
                                    (0, l.default)(t) && (t = n);
                            }),
                            t
                        );
                    },
                    onEscape: function () {
                        this.search.length
                            ? (this.search = "")
                            : this.$refs.search.blur();
                    },
                    onSearchBlur: function () {
                        this.mousedown && !this.searching
                            ? (this.mousedown = !1)
                            : (this.clearSearchOnBlur && (this.search = ""),
                              (this.open = !1),
                              this.$emit("search:blur"));
                    },
                    onSearchFocus: function () {
                        (this.open = !0), this.$emit("search:focus");
                    },
                    maybeDeleteValue: function () {
                        if (
                            !this.$refs.search.value.length &&
                            this.mutableValue
                        )
                            return this.multiple
                                ? this.mutableValue.pop()
                                : (this.mutableValue = null);
                    },
                    optionExists: function (t) {
                        var e = this,
                            n = !1;
                        return (
                            this.mutableOptions.forEach(function (o) {
                                "object" ===
                                    ("undefined" == typeof o
                                        ? "undefined"
                                        : (0, f.default)(o)) && o[e.label] === t
                                    ? (n = !0)
                                    : o === t && (n = !0);
                            }),
                            n
                        );
                    },
                    maybePushTag: function (t) {
                        this.pushTags && this.mutableOptions.push(t);
                    },
                    onMousedown: function () {
                        this.mousedown = !0;
                    },
                },
                computed: {
                    dropdownClasses: function () {
                        return {
                            open: this.dropdownOpen,
                            single: !this.multiple,
                            searching: this.searching,
                            searchable: this.searchable,
                            unsearchable: !this.searchable,
                            loading: this.mutableLoading,
                            rtl: "rtl" === this.dir,
                            disabled: this.disabled,
                        };
                    },
                    clearSearchOnBlur: function () {
                        return this.clearSearchOnSelect && !this.multiple;
                    },
                    searching: function () {
                        return !!this.search;
                    },
                    dropdownOpen: function () {
                        return (
                            !this.noDrop && this.open && !this.mutableLoading
                        );
                    },
                    searchPlaceholder: function () {
                        if (this.isValueEmpty && this.placeholder)
                            return this.placeholder;
                    },
                    filteredOptions: function () {
                        if (!this.filterable && !this.taggable)
                            return this.mutableOptions.slice();
                        var t = this.search.length
                            ? this.filter(
                                  this.mutableOptions,
                                  this.search,
                                  this
                              )
                            : this.mutableOptions;
                        return (
                            this.taggable &&
                                this.search.length &&
                                !this.optionExists(this.search) &&
                                t.unshift(this.search),
                            t
                        );
                    },
                    isValueEmpty: function () {
                        return (
                            !this.mutableValue ||
                            ("object" === (0, f.default)(this.mutableValue)
                                ? !(0, i.default)(this.mutableValue).length
                                : !this.valueAsArray.length)
                        );
                    },
                    valueAsArray: function () {
                        return this.multiple && this.mutableValue
                            ? this.mutableValue
                            : this.mutableValue
                            ? [].concat(this.mutableValue)
                            : [];
                    },
                    showClearButton: function () {
                        return (
                            !this.multiple &&
                            this.clearable &&
                            !this.open &&
                            null != this.mutableValue
                        );
                    },
                },
            };
        },
        function (t, e, n) {
            "use strict";
            function o(t) {
                return t && t.__esModule ? t : { default: t };
            }
            Object.defineProperty(e, "__esModule", { value: !0 });
            var r = n(28),
                i = o(r),
                s = n(30),
                a = o(s),
                u = n(29),
                l = o(u);
            e.default = {
                ajax: i.default,
                pointer: a.default,
                pointerScroll: l.default,
            };
        },
        function (t, e, n) {
            t.exports = { default: n(50), __esModule: !0 };
        },
        function (t, e, n) {
            t.exports = { default: n(51), __esModule: !0 };
        },
        function (t, e, n) {
            t.exports = { default: n(52), __esModule: !0 };
        },
        function (t, e, n) {
            t.exports = { default: n(53), __esModule: !0 };
        },
        function (t, e, n) {
            t.exports = { default: n(54), __esModule: !0 };
        },
        function (t, e, n) {
            "use strict";
            function o(t) {
                return t && t.__esModule ? t : { default: t };
            }
            e.__esModule = !0;
            var r = n(44),
                i = o(r);
            e.default = function (t, e, n) {
                return (
                    e in t
                        ? (0, i.default)(t, e, {
                              value: n,
                              enumerable: !0,
                              configurable: !0,
                              writable: !0,
                          })
                        : (t[e] = n),
                    t
                );
            };
        },
        function (t, e, n) {
            "use strict";
            function o(t) {
                return t && t.__esModule ? t : { default: t };
            }
            e.__esModule = !0;
            var r = n(47),
                i = o(r),
                s = n(46),
                a = o(s),
                u =
                    "function" == typeof a.default &&
                    "symbol" == typeof i.default
                        ? function (t) {
                              return typeof t;
                          }
                        : function (t) {
                              return t &&
                                  "function" == typeof a.default &&
                                  t.constructor === a.default &&
                                  t !== a.default.prototype
                                  ? "symbol"
                                  : typeof t;
                          };
            e.default =
                "function" == typeof a.default && "symbol" === u(i.default)
                    ? function (t) {
                          return "undefined" == typeof t ? "undefined" : u(t);
                      }
                    : function (t) {
                          return t &&
                              "function" == typeof a.default &&
                              t.constructor === a.default &&
                              t !== a.default.prototype
                              ? "symbol"
                              : "undefined" == typeof t
                              ? "undefined"
                              : u(t);
                      };
        },
        function (t, e, n) {
            var o = n(2),
                r = o.JSON || (o.JSON = { stringify: JSON.stringify });
            t.exports = function (t) {
                return r.stringify.apply(r, arguments);
            };
        },
        function (t, e, n) {
            n(75);
            var o = n(2).Object;
            t.exports = function (t, e, n) {
                return o.defineProperty(t, e, n);
            };
        },
        function (t, e, n) {
            n(76), (t.exports = n(2).Object.keys);
        },
        function (t, e, n) {
            n(79), n(77), n(80), n(81), (t.exports = n(2).Symbol);
        },
        function (t, e, n) {
            n(78), n(82), (t.exports = n(27).f("iterator"));
        },
        function (t, e) {
            t.exports = function (t) {
                if ("function" != typeof t)
                    throw TypeError(t + " is not a function!");
                return t;
            };
        },
        function (t, e) {
            t.exports = function () {};
        },
        function (t, e, n) {
            var o = n(7),
                r = n(73),
                i = n(72);
            t.exports = function (t) {
                return function (e, n, s) {
                    var a,
                        u = o(e),
                        l = r(u.length),
                        c = i(s, l);
                    if (t && n != n) {
                        for (; l > c; ) if (((a = u[c++]), a != a)) return !0;
                    } else
                        for (; l > c; c++)
                            if ((t || c in u) && u[c] === n) return t || c || 0;
                    return !t && -1;
                };
            };
        },
        function (t, e, n) {
            var o = n(55);
            t.exports = function (t, e, n) {
                if ((o(t), void 0 === e)) return t;
                switch (n) {
                    case 1:
                        return function (n) {
                            return t.call(e, n);
                        };
                    case 2:
                        return function (n, o) {
                            return t.call(e, n, o);
                        };
                    case 3:
                        return function (n, o, r) {
                            return t.call(e, n, o, r);
                        };
                }
                return function () {
                    return t.apply(e, arguments);
                };
            };
        },
        function (t, e, n) {
            var o = n(13),
                r = n(37),
                i = n(20);
            t.exports = function (t) {
                var e = o(t),
                    n = r.f;
                if (n)
                    for (var s, a = n(t), u = i.f, l = 0; a.length > l; )
                        u.call(t, (s = a[l++])) && e.push(s);
                return e;
            };
        },
        function (t, e, n) {
            var o = n(1).document;
            t.exports = o && o.documentElement;
        },
        function (t, e, n) {
            var o = n(31);
            t.exports = Object("z").propertyIsEnumerable(0)
                ? Object
                : function (t) {
                      return "String" == o(t) ? t.split("") : Object(t);
                  };
        },
        function (t, e, n) {
            var o = n(31);
            t.exports =
                Array.isArray ||
                function (t) {
                    return "Array" == o(t);
                };
        },
        function (t, e, n) {
            "use strict";
            var o = n(35),
                r = n(14),
                i = n(21),
                s = {};
            n(6)(s, n(8)("iterator"), function () {
                return this;
            }),
                (t.exports = function (t, e, n) {
                    (t.prototype = o(s, { next: r(1, n) })),
                        i(t, e + " Iterator");
                });
        },
        function (t, e) {
            t.exports = function (t, e) {
                return { value: e, done: !!t };
            };
        },
        function (t, e, n) {
            var o = n(15)("meta"),
                r = n(10),
                i = n(4),
                s = n(5).f,
                a = 0,
                u =
                    Object.isExtensible ||
                    function () {
                        return !0;
                    },
                l = !n(9)(function () {
                    return u(Object.preventExtensions({}));
                }),
                c = function (t) {
                    s(t, o, { value: { i: "O" + ++a, w: {} } });
                },
                f = function (t, e) {
                    if (!r(t))
                        return "symbol" == typeof t
                            ? t
                            : ("string" == typeof t ? "S" : "P") + t;
                    if (!i(t, o)) {
                        if (!u(t)) return "F";
                        if (!e) return "E";
                        c(t);
                    }
                    return t[o].i;
                },
                p = function (t, e) {
                    if (!i(t, o)) {
                        if (!u(t)) return !0;
                        if (!e) return !1;
                        c(t);
                    }
                    return t[o].w;
                },
                d = function (t) {
                    return l && h.NEED && u(t) && !i(t, o) && c(t), t;
                },
                h = (t.exports = {
                    KEY: o,
                    NEED: !1,
                    fastKey: f,
                    getWeak: p,
                    onFreeze: d,
                });
        },
        function (t, e, n) {
            var o = n(5),
                r = n(11),
                i = n(13);
            t.exports = n(3)
                ? Object.defineProperties
                : function (t, e) {
                      r(t);
                      for (var n, s = i(e), a = s.length, u = 0; a > u; )
                          o.f(t, (n = s[u++]), e[n]);
                      return t;
                  };
        },
        function (t, e, n) {
            var o = n(20),
                r = n(14),
                i = n(7),
                s = n(25),
                a = n(4),
                u = n(33),
                l = Object.getOwnPropertyDescriptor;
            e.f = n(3)
                ? l
                : function (t, e) {
                      if (((t = i(t)), (e = s(e, !0)), u))
                          try {
                              return l(t, e);
                          } catch (t) {}
                      if (a(t, e)) return r(!o.f.call(t, e), t[e]);
                  };
        },
        function (t, e, n) {
            var o = n(7),
                r = n(36).f,
                i = {}.toString,
                s =
                    "object" == typeof window &&
                    window &&
                    Object.getOwnPropertyNames
                        ? Object.getOwnPropertyNames(window)
                        : [],
                a = function (t) {
                    try {
                        return r(t);
                    } catch (t) {
                        return s.slice();
                    }
                };
            t.exports.f = function (t) {
                return s && "[object Window]" == i.call(t) ? a(t) : r(o(t));
            };
        },
        function (t, e, n) {
            var o = n(4),
                r = n(40),
                i = n(22)("IE_PROTO"),
                s = Object.prototype;
            t.exports =
                Object.getPrototypeOf ||
                function (t) {
                    return (
                        (t = r(t)),
                        o(t, i)
                            ? t[i]
                            : "function" == typeof t.constructor &&
                              t instanceof t.constructor
                            ? t.constructor.prototype
                            : t instanceof Object
                            ? s
                            : null
                    );
                };
        },
        function (t, e, n) {
            var o = n(12),
                r = n(2),
                i = n(9);
            t.exports = function (t, e) {
                var n = (r.Object || {})[t] || Object[t],
                    s = {};
                (s[t] = e(n)),
                    o(
                        o.S +
                            o.F *
                                i(function () {
                                    n(1);
                                }),
                        "Object",
                        s
                    );
            };
        },
        function (t, e, n) {
            var o = n(24),
                r = n(16);
            t.exports = function (t) {
                return function (e, n) {
                    var i,
                        s,
                        a = String(r(e)),
                        u = o(n),
                        l = a.length;
                    return u < 0 || u >= l
                        ? t
                            ? ""
                            : void 0
                        : ((i = a.charCodeAt(u)),
                          i < 55296 ||
                          i > 56319 ||
                          u + 1 === l ||
                          (s = a.charCodeAt(u + 1)) < 56320 ||
                          s > 57343
                              ? t
                                  ? a.charAt(u)
                                  : i
                              : t
                              ? a.slice(u, u + 2)
                              : ((i - 55296) << 10) + (s - 56320) + 65536);
                };
            };
        },
        function (t, e, n) {
            var o = n(24),
                r = Math.max,
                i = Math.min;
            t.exports = function (t, e) {
                return (t = o(t)), t < 0 ? r(t + e, 0) : i(t, e);
            };
        },
        function (t, e, n) {
            var o = n(24),
                r = Math.min;
            t.exports = function (t) {
                return t > 0 ? r(o(t), 9007199254740991) : 0;
            };
        },
        function (t, e, n) {
            "use strict";
            var o = n(56),
                r = n(64),
                i = n(18),
                s = n(7);
            (t.exports = n(34)(
                Array,
                "Array",
                function (t, e) {
                    (this._t = s(t)), (this._i = 0), (this._k = e);
                },
                function () {
                    var t = this._t,
                        e = this._k,
                        n = this._i++;
                    return !t || n >= t.length
                        ? ((this._t = void 0), r(1))
                        : "keys" == e
                        ? r(0, n)
                        : "values" == e
                        ? r(0, t[n])
                        : r(0, [n, t[n]]);
                },
                "values"
            )),
                (i.Arguments = i.Array),
                o("keys"),
                o("values"),
                o("entries");
        },
        function (t, e, n) {
            var o = n(12);
            o(o.S + o.F * !n(3), "Object", { defineProperty: n(5).f });
        },
        function (t, e, n) {
            var o = n(40),
                r = n(13);
            n(70)("keys", function () {
                return function (t) {
                    return r(o(t));
                };
            });
        },
        function (t, e) {},
        function (t, e, n) {
            "use strict";
            var o = n(71)(!0);
            n(34)(
                String,
                "String",
                function (t) {
                    (this._t = String(t)), (this._i = 0);
                },
                function () {
                    var t,
                        e = this._t,
                        n = this._i;
                    return n >= e.length
                        ? { value: void 0, done: !0 }
                        : ((t = o(e, n)),
                          (this._i += t.length),
                          { value: t, done: !1 });
                }
            );
        },
        function (t, e, n) {
            "use strict";
            var o = n(1),
                r = n(4),
                i = n(3),
                s = n(12),
                a = n(39),
                u = n(65).KEY,
                l = n(9),
                c = n(23),
                f = n(21),
                p = n(15),
                d = n(8),
                h = n(27),
                b = n(26),
                v = n(59),
                g = n(62),
                y = n(11),
                m = n(10),
                x = n(7),
                w = n(25),
                S = n(14),
                O = n(35),
                _ = n(68),
                j = n(67),
                k = n(5),
                P = n(13),
                A = j.f,
                C = k.f,
                M = _.f,
                L = o.Symbol,
                T = o.JSON,
                E = T && T.stringify,
                V = "prototype",
                B = d("_hidden"),
                F = d("toPrimitive"),
                N = {}.propertyIsEnumerable,
                $ = c("symbol-registry"),
                D = c("symbols"),
                I = c("op-symbols"),
                R = Object[V],
                z = "function" == typeof L,
                H = o.QObject,
                G = !H || !H[V] || !H[V].findChild,
                J =
                    i &&
                    l(function () {
                        return (
                            7 !=
                            O(
                                C({}, "a", {
                                    get: function () {
                                        return C(this, "a", { value: 7 }).a;
                                    },
                                })
                            ).a
                        );
                    })
                        ? function (t, e, n) {
                              var o = A(R, e);
                              o && delete R[e],
                                  C(t, e, n),
                                  o && t !== R && C(R, e, o);
                          }
                        : C,
                U = function (t) {
                    var e = (D[t] = O(L[V]));
                    return (e._k = t), e;
                },
                W =
                    z && "symbol" == typeof L.iterator
                        ? function (t) {
                              return "symbol" == typeof t;
                          }
                        : function (t) {
                              return t instanceof L;
                          },
                K = function (t, e, n) {
                    return (
                        t === R && K(I, e, n),
                        y(t),
                        (e = w(e, !0)),
                        y(n),
                        r(D, e)
                            ? (n.enumerable
                                  ? (r(t, B) && t[B][e] && (t[B][e] = !1),
                                    (n = O(n, { enumerable: S(0, !1) })))
                                  : (r(t, B) || C(t, B, S(1, {})),
                                    (t[B][e] = !0)),
                              J(t, e, n))
                            : C(t, e, n)
                    );
                },
                Y = function (t, e) {
                    y(t);
                    for (var n, o = v((e = x(e))), r = 0, i = o.length; i > r; )
                        K(t, (n = o[r++]), e[n]);
                    return t;
                },
                q = function (t, e) {
                    return void 0 === e ? O(t) : Y(O(t), e);
                },
                Q = function (t) {
                    var e = N.call(this, (t = w(t, !0)));
                    return (
                        !(this === R && r(D, t) && !r(I, t)) &&
                        (!(
                            e ||
                            !r(this, t) ||
                            !r(D, t) ||
                            (r(this, B) && this[B][t])
                        ) ||
                            e)
                    );
                },
                Z = function (t, e) {
                    if (
                        ((t = x(t)),
                        (e = w(e, !0)),
                        t !== R || !r(D, e) || r(I, e))
                    ) {
                        var n = A(t, e);
                        return (
                            !n ||
                                !r(D, e) ||
                                (r(t, B) && t[B][e]) ||
                                (n.enumerable = !0),
                            n
                        );
                    }
                },
                X = function (t) {
                    for (var e, n = M(x(t)), o = [], i = 0; n.length > i; )
                        r(D, (e = n[i++])) || e == B || e == u || o.push(e);
                    return o;
                },
                tt = function (t) {
                    for (
                        var e, n = t === R, o = M(n ? I : x(t)), i = [], s = 0;
                        o.length > s;

                    )
                        !r(D, (e = o[s++])) || (n && !r(R, e)) || i.push(D[e]);
                    return i;
                };
            z ||
                ((L = function () {
                    if (this instanceof L)
                        throw TypeError("Symbol is not a constructor!");
                    var t = p(arguments.length > 0 ? arguments[0] : void 0),
                        e = function (n) {
                            this === R && e.call(I, n),
                                r(this, B) &&
                                    r(this[B], t) &&
                                    (this[B][t] = !1),
                                J(this, t, S(1, n));
                        };
                    return (
                        i && G && J(R, t, { configurable: !0, set: e }), U(t)
                    );
                }),
                a(L[V], "toString", function () {
                    return this._k;
                }),
                (j.f = Z),
                (k.f = K),
                (n(36).f = _.f = X),
                (n(20).f = Q),
                (n(37).f = tt),
                i && !n(19) && a(R, "propertyIsEnumerable", Q, !0),
                (h.f = function (t) {
                    return U(d(t));
                })),
                s(s.G + s.W + s.F * !z, { Symbol: L });
            for (
                var et =
                        "hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables".split(
                            ","
                        ),
                    nt = 0;
                et.length > nt;

            )
                d(et[nt++]);
            for (var ot = P(d.store), rt = 0; ot.length > rt; ) b(ot[rt++]);
            s(s.S + s.F * !z, "Symbol", {
                for: function (t) {
                    return r($, (t += "")) ? $[t] : ($[t] = L(t));
                },
                keyFor: function (t) {
                    if (!W(t)) throw TypeError(t + " is not a symbol!");
                    for (var e in $) if ($[e] === t) return e;
                },
                useSetter: function () {
                    G = !0;
                },
                useSimple: function () {
                    G = !1;
                },
            }),
                s(s.S + s.F * !z, "Object", {
                    create: q,
                    defineProperty: K,
                    defineProperties: Y,
                    getOwnPropertyDescriptor: Z,
                    getOwnPropertyNames: X,
                    getOwnPropertySymbols: tt,
                }),
                T &&
                    s(
                        s.S +
                            s.F *
                                (!z ||
                                    l(function () {
                                        var t = L();
                                        return (
                                            "[null]" != E([t]) ||
                                            "{}" != E({ a: t }) ||
                                            "{}" != E(Object(t))
                                        );
                                    })),
                        "JSON",
                        {
                            stringify: function (t) {
                                for (
                                    var e, n, o = [t], r = 1;
                                    arguments.length > r;

                                )
                                    o.push(arguments[r++]);
                                if (
                                    ((n = e = o[1]),
                                    (m(e) || void 0 !== t) && !W(t))
                                )
                                    return (
                                        g(e) ||
                                            (e = function (t, e) {
                                                if (
                                                    ("function" == typeof n &&
                                                        (e = n.call(
                                                            this,
                                                            t,
                                                            e
                                                        )),
                                                    !W(e))
                                                )
                                                    return e;
                                            }),
                                        (o[1] = e),
                                        E.apply(T, o)
                                    );
                            },
                        }
                    ),
                L[V][F] || n(6)(L[V], F, L[V].valueOf),
                f(L, "Symbol"),
                f(Math, "Math", !0),
                f(o.JSON, "JSON", !0);
        },
        function (t, e, n) {
            n(26)("asyncIterator");
        },
        function (t, e, n) {
            n(26)("observable");
        },
        function (t, e, n) {
            n(74);
            for (
                var o = n(1),
                    r = n(6),
                    i = n(18),
                    s = n(8)("toStringTag"),
                    a =
                        "CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,TextTrackList,TouchList".split(
                            ","
                        ),
                    u = 0;
                u < a.length;
                u++
            ) {
                var l = a[u],
                    c = o[l],
                    f = c && c.prototype;
                f && !f[s] && r(f, s, l), (i[l] = i.Array);
            }
        },
        function (t, e, n) {
            (e = t.exports = n(84)()),
                e.push([
                    t.id,
                    '.v-select{position:relative;font-family:inherit}.v-select,.v-select *{box-sizing:border-box}.v-select[dir=rtl] .vs__actions{padding:0 3px 0 6px}.v-select[dir=rtl] .dropdown-toggle .clear{margin-left:6px;margin-right:0}.v-select[dir=rtl] .selected-tag .close{margin-left:0;margin-right:2px}.v-select[dir=rtl] .dropdown-menu{text-align:right}.v-select .open-indicator{display:flex;align-items:center;cursor:pointer;pointer-events:all;opacity:1;width:12px}.v-select .open-indicator,.v-select .open-indicator:before{transition:all .15s cubic-bezier(1,-.115,.975,.855);transition-timing-function:cubic-bezier(1,-.115,.975,.855)}.v-select .open-indicator:before{border-color:rgba(60,60,60,.5);border-style:solid;border-width:3px 3px 0 0;content:"";display:inline-block;height:10px;width:10px;vertical-align:text-top;transform:rotate(133deg);box-sizing:inherit}.v-select.open .open-indicator:before{transform:rotate(315deg)}.v-select.loading .open-indicator{opacity:0}.v-select .dropdown-toggle{-webkit-appearance:none;-moz-appearance:none;appearance:none;display:flex;padding:0 0 4px;background:none;border:1px solid rgba(60,60,60,.26);border-radius:4px;white-space:normal}.v-select .vs__selected-options{display:flex;flex-basis:100%;flex-grow:1;flex-wrap:wrap;padding:0 2px;position:relative}.v-select .vs__actions{display:flex;align-items:stretch;padding:0 6px 0 3px}.v-select .dropdown-toggle .clear{font-size:23px;font-weight:700;line-height:1;color:rgba(60,60,60,.5);padding:0;border:0;background-color:transparent;cursor:pointer;margin-right:6px}.v-select.searchable .dropdown-toggle{cursor:text}.v-select.unsearchable .dropdown-toggle{cursor:pointer}.v-select.open .dropdown-toggle{border-bottom-color:transparent;border-bottom-left-radius:0;border-bottom-right-radius:0}.v-select .dropdown-menu{display:block;position:absolute;top:100%;left:0;z-index:1000;min-width:160px;padding:5px 0;margin:0;width:100%;overflow-y:scroll;border:1px solid rgba(0,0,0,.26);box-shadow:0 3px 6px 0 rgba(0,0,0,.15);border-top:none;border-radius:0 0 4px 4px;text-align:left;list-style:none;background:#fff}.v-select .no-options{text-align:center}.v-select .selected-tag{display:flex;align-items:center;background-color:#f0f0f0;border:1px solid #ccc;border-radius:4px;color:#333;line-height:1.42857143;margin:4px 2px 0;padding:0 .25em;transition:opacity .25s}.v-select.single .selected-tag{background-color:transparent;border-color:transparent}.v-select.single.open .selected-tag{position:absolute;opacity:.4}.v-select.single.searching .selected-tag{display:none}.v-select .selected-tag .close{margin-left:2px;font-size:1.25em;appearance:none;padding:0;cursor:pointer;background:0 0;border:0;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;filter:alpha(opacity=20);opacity:.2}.v-select.single.searching:not(.open):not(.loading) input[type=search]{opacity:.2}.v-select input[type=search]::-webkit-search-cancel-button,.v-select input[type=search]::-webkit-search-decoration,.v-select input[type=search]::-webkit-search-results-button,.v-select input[type=search]::-webkit-search-results-decoration{display:none}.v-select input[type=search]::-ms-clear{display:none}.v-select input[type=search],.v-select input[type=search]:focus{appearance:none;-webkit-appearance:none;-moz-appearance:none;line-height:1.42857143;font-size:1em;display:inline-block;border:1px solid transparent;border-left:none;outline:none;margin:4px 0 0;padding:0 7px;max-width:100%;background:none;box-shadow:none;flex-grow:1;width:0}.v-select.unsearchable input[type=search]{opacity:0}.v-select.unsearchable input[type=search]:hover{cursor:pointer}.v-select li{line-height:1.42857143}.v-select li>a{display:block;padding:3px 20px;clear:both;color:#333;white-space:nowrap}.v-select li:hover{cursor:pointer}.v-select .dropdown-menu .active>a{color:#333;background:rgba(50,50,50,.1)}.v-select .dropdown-menu>.highlight>a{background:#5897fb;color:#fff}.v-select .highlight:not(:last-child){margin-bottom:0}.v-select .spinner{align-self:center;opacity:0;font-size:5px;text-indent:-9999em;overflow:hidden;border-top:.9em solid hsla(0,0%,39%,.1);border-right:.9em solid hsla(0,0%,39%,.1);border-bottom:.9em solid hsla(0,0%,39%,.1);border-left:.9em solid rgba(60,60,60,.45);transform:translateZ(0);animation:vSelectSpinner 1.1s infinite linear;transition:opacity .1s}.v-select .spinner,.v-select .spinner:after{border-radius:50%;width:5em;height:5em}.v-select.disabled .dropdown-toggle,.v-select.disabled .dropdown-toggle .clear,.v-select.disabled .dropdown-toggle input,.v-select.disabled .open-indicator,.v-select.disabled .selected-tag .close{cursor:not-allowed;background-color:#f8f8f8}.v-select.loading .spinner{opacity:1}@-webkit-keyframes vSelectSpinner{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}@keyframes vSelectSpinner{0%{transform:rotate(0deg)}to{transform:rotate(1turn)}}.fade-enter-active,.fade-leave-active{transition:opacity .15s cubic-bezier(1,.5,.8,1)}.fade-enter,.fade-leave-to{opacity:0}',
                    "",
                ]);
        },
        function (t, e) {
            t.exports = function () {
                var t = [];
                return (
                    (t.toString = function () {
                        for (var t = [], e = 0; e < this.length; e++) {
                            var n = this[e];
                            n[2]
                                ? t.push("@media " + n[2] + "{" + n[1] + "}")
                                : t.push(n[1]);
                        }
                        return t.join("");
                    }),
                    (t.i = function (e, n) {
                        "string" == typeof e && (e = [[null, e, ""]]);
                        for (var o = {}, r = 0; r < this.length; r++) {
                            var i = this[r][0];
                            "number" == typeof i && (o[i] = !0);
                        }
                        for (r = 0; r < e.length; r++) {
                            var s = e[r];
                            ("number" == typeof s[0] && o[s[0]]) ||
                                (n && !s[2]
                                    ? (s[2] = n)
                                    : n &&
                                      (s[2] = "(" + s[2] + ") and (" + n + ")"),
                                t.push(s));
                        }
                    }),
                    t
                );
            };
        },
        function (t, e, n) {
            n(89);
            var o = n(86)(n(41), n(87), null, null);
            t.exports = o.exports;
        },
        function (t, e) {
            t.exports = function (t, e, n, o) {
                var r,
                    i = (t = t || {}),
                    s = typeof t.default;
                ("object" !== s && "function" !== s) ||
                    ((r = t), (i = t.default));
                var a = "function" == typeof i ? i.options : i;
                if (
                    (e &&
                        ((a.render = e.render),
                        (a.staticRenderFns = e.staticRenderFns)),
                    n && (a._scopeId = n),
                    o)
                ) {
                    var u = a.computed || (a.computed = {});
                    Object.keys(o).forEach(function (t) {
                        var e = o[t];
                        u[t] = function () {
                            return e;
                        };
                    });
                }
                return { esModule: r, exports: i, options: a };
            };
        },
        function (t, e) {
            t.exports = {
                render: function () {
                    var t = this,
                        e = t.$createElement,
                        n = t._self._c || e;
                    return n(
                        "div",
                        {
                            staticClass: "dropdown v-select",
                            class: t.dropdownClasses,
                            attrs: { dir: t.dir },
                        },
                        [
                            n(
                                "div",
                                {
                                    ref: "toggle",
                                    staticClass: "dropdown-toggle",
                                    on: {
                                        mousedown: function (e) {
                                            e.preventDefault(),
                                                t.toggleDropdown(e);
                                        },
                                    },
                                },
                                [
                                    n(
                                        "div",
                                        {
                                            ref: "selectedOptions",
                                            staticClass: "vs__selected-options",
                                        },
                                        [
                                            t._l(t.valueAsArray, function (e) {
                                                return t._t(
                                                    "selected-option-container",
                                                    [
                                                        n(
                                                            "span",
                                                            {
                                                                key: e.index,
                                                                staticClass:
                                                                    "selected-tag",
                                                            },
                                                            [
                                                                t._t(
                                                                    "selected-option",
                                                                    [
                                                                        t._v(
                                                                            "\n            " +
                                                                                t._s(
                                                                                    t.getOptionLabel(
                                                                                        e
                                                                                    )
                                                                                ) +
                                                                                "\n          "
                                                                        ),
                                                                    ],
                                                                    null,
                                                                    "object" ==
                                                                        typeof e
                                                                        ? e
                                                                        : ((o =
                                                                              {}),
                                                                          (o[
                                                                              t.label
                                                                          ] =
                                                                              e),
                                                                          o)
                                                                ),
                                                                t._v(" "),
                                                                t.multiple
                                                                    ? n(
                                                                          "button",
                                                                          {
                                                                              staticClass:
                                                                                  "close",
                                                                              attrs: {
                                                                                  disabled:
                                                                                      t.disabled,
                                                                                  type: "button",
                                                                                  "aria-label":
                                                                                      "Remove option",
                                                                              },
                                                                              on: {
                                                                                  click: function (
                                                                                      n
                                                                                  ) {
                                                                                      t.deselect(
                                                                                          e
                                                                                      );
                                                                                  },
                                                                              },
                                                                          },
                                                                          [
                                                                              n(
                                                                                  "span",
                                                                                  {
                                                                                      attrs: {
                                                                                          "aria-hidden":
                                                                                              "true",
                                                                                      },
                                                                                  },
                                                                                  [
                                                                                      t._v(
                                                                                          "×"
                                                                                      ),
                                                                                  ]
                                                                              ),
                                                                          ]
                                                                      )
                                                                    : t._e(),
                                                            ],
                                                            2
                                                        ),
                                                    ],
                                                    {
                                                        option:
                                                            "object" == typeof e
                                                                ? e
                                                                : ((r = {}),
                                                                  (r[t.label] =
                                                                      e),
                                                                  r),
                                                        deselect: t.deselect,
                                                        multiple: t.multiple,
                                                        disabled: t.disabled,
                                                    }
                                                );
                                                var o, r;
                                            }),
                                            t._v(" "),
                                            n("input", {
                                                directives: [
                                                    {
                                                        name: "model",
                                                        rawName: "v-model",
                                                        value: t.search,
                                                        expression: "search",
                                                    },
                                                ],
                                                ref: "search",
                                                staticClass: "form-control",
                                                attrs: {
                                                    type: "search",
                                                    autocomplete: "off",
                                                    disabled: t.disabled,
                                                    placeholder:
                                                        t.searchPlaceholder,
                                                    tabindex: t.tabindex,
                                                    readonly: !t.searchable,
                                                    id: t.inputId,
                                                    role: "combobox",
                                                    "aria-expanded":
                                                        t.dropdownOpen,
                                                    "aria-label":
                                                        "Search for option",
                                                },
                                                domProps: { value: t.search },
                                                on: {
                                                    keydown: [
                                                        function (e) {
                                                            return "button" in
                                                                e ||
                                                                !t._k(
                                                                    e.keyCode,
                                                                    "delete",
                                                                    [8, 46],
                                                                    e.key
                                                                )
                                                                ? void t.maybeDeleteValue(
                                                                      e
                                                                  )
                                                                : null;
                                                        },
                                                        function (e) {
                                                            return "button" in
                                                                e ||
                                                                !t._k(
                                                                    e.keyCode,
                                                                    "up",
                                                                    38,
                                                                    e.key
                                                                )
                                                                ? (e.preventDefault(),
                                                                  void t.typeAheadUp(
                                                                      e
                                                                  ))
                                                                : null;
                                                        },
                                                        function (e) {
                                                            return "button" in
                                                                e ||
                                                                !t._k(
                                                                    e.keyCode,
                                                                    "down",
                                                                    40,
                                                                    e.key
                                                                )
                                                                ? (e.preventDefault(),
                                                                  void t.typeAheadDown(
                                                                      e
                                                                  ))
                                                                : null;
                                                        },
                                                        function (e) {
                                                            return "button" in
                                                                e ||
                                                                !t._k(
                                                                    e.keyCode,
                                                                    "enter",
                                                                    13,
                                                                    e.key
                                                                )
                                                                ? (e.preventDefault(),
                                                                  void t.typeAheadSelect(
                                                                      e
                                                                  ))
                                                                : null;
                                                        },
                                                        function (e) {
                                                            return "button" in
                                                                e ||
                                                                !t._k(
                                                                    e.keyCode,
                                                                    "tab",
                                                                    9,
                                                                    e.key
                                                                )
                                                                ? void t.onTab(
                                                                      e
                                                                  )
                                                                : null;
                                                        },
                                                    ],
                                                    keyup: function (e) {
                                                        return "button" in e ||
                                                            !t._k(
                                                                e.keyCode,
                                                                "esc",
                                                                27,
                                                                e.key
                                                            )
                                                            ? void t.onEscape(e)
                                                            : null;
                                                    },
                                                    blur: t.onSearchBlur,
                                                    focus: t.onSearchFocus,
                                                    input: function (e) {
                                                        e.target.composing ||
                                                            (t.search =
                                                                e.target.value);
                                                    },
                                                },
                                            }),
                                        ],
                                        2
                                    ),
                                    t._v(" "),
                                    n(
                                        "div",
                                        { staticClass: "vs__actions" },
                                        [
                                            n(
                                                "button",
                                                {
                                                    directives: [
                                                        {
                                                            name: "show",
                                                            rawName: "v-show",
                                                            value: t.showClearButton,
                                                            expression:
                                                                "showClearButton",
                                                        },
                                                    ],
                                                    staticClass: "clear",
                                                    attrs: {
                                                        disabled: t.disabled,
                                                        type: "button",
                                                        title: "Clear selection",
                                                    },
                                                    on: {
                                                        click: t.clearSelection,
                                                    },
                                                },
                                                [
                                                    n(
                                                        "span",
                                                        {
                                                            attrs: {
                                                                "aria-hidden":
                                                                    "true",
                                                            },
                                                        },
                                                        [t._v("×")]
                                                    ),
                                                ]
                                            ),
                                            t._v(" "),
                                            t.noDrop
                                                ? t._e()
                                                : n("i", {
                                                      ref: "openIndicator",
                                                      staticClass:
                                                          "open-indicator",
                                                      attrs: {
                                                          role: "presentation",
                                                      },
                                                  }),
                                            t._v(" "),
                                            t._t("spinner", [
                                                n(
                                                    "div",
                                                    {
                                                        directives: [
                                                            {
                                                                name: "show",
                                                                rawName:
                                                                    "v-show",
                                                                value: t.mutableLoading,
                                                                expression:
                                                                    "mutableLoading",
                                                            },
                                                        ],
                                                        staticClass: "spinner",
                                                    },
                                                    [t._v("Loading...")]
                                                ),
                                            ]),
                                        ],
                                        2
                                    ),
                                ]
                            ),
                            t._v(" "),
                            n("transition", { attrs: { name: t.transition } }, [
                                t.dropdownOpen
                                    ? n(
                                          "ul",
                                          {
                                              ref: "dropdownMenu",
                                              staticClass: "dropdown-menu",
                                              style: {
                                                  "max-height": t.maxHeight,
                                              },
                                              attrs: { role: "listbox" },
                                              on: { mousedown: t.onMousedown },
                                          },
                                          [
                                              t._l(
                                                  t.filteredOptions,
                                                  function (e, o) {
                                                      return n(
                                                          "li",
                                                          {
                                                              key: o,
                                                              class: {
                                                                  active: t.isOptionSelected(
                                                                      e
                                                                  ),
                                                                  highlight:
                                                                      o ===
                                                                      t.typeAheadPointer,
                                                              },
                                                              attrs: {
                                                                  role: "option",
                                                              },
                                                              on: {
                                                                  mouseover:
                                                                      function (
                                                                          e
                                                                      ) {
                                                                          t.typeAheadPointer =
                                                                              o;
                                                                      },
                                                              },
                                                          },
                                                          [
                                                              n(
                                                                  "a",
                                                                  {
                                                                      on: {
                                                                          mousedown:
                                                                              function (
                                                                                  n
                                                                              ) {
                                                                                  n.preventDefault(),
                                                                                      n.stopPropagation(),
                                                                                      t.select(
                                                                                          e
                                                                                      );
                                                                              },
                                                                      },
                                                                  },
                                                                  [
                                                                      t._t(
                                                                          "option",
                                                                          [
                                                                              t._v(
                                                                                  "\n          " +
                                                                                      t._s(
                                                                                          t.getOptionLabel(
                                                                                              e
                                                                                          )
                                                                                      ) +
                                                                                      "\n        "
                                                                              ),
                                                                          ],
                                                                          null,
                                                                          "object" ==
                                                                              typeof e
                                                                              ? e
                                                                              : ((r =
                                                                                    {}),
                                                                                (r[
                                                                                    t.label
                                                                                ] =
                                                                                    e),
                                                                                r)
                                                                      ),
                                                                  ],
                                                                  2
                                                              ),
                                                          ]
                                                      );
                                                      var r;
                                                  }
                                              ),
                                              t._v(" "),
                                              t.filteredOptions.length
                                                  ? t._e()
                                                  : n(
                                                        "li",
                                                        {
                                                            staticClass:
                                                                "no-options",
                                                        },
                                                        [
                                                            t._t("no-options", [
                                                                t._v(
                                                                    "No options."
                                                                ),
                                                            ]),
                                                        ],
                                                        2
                                                    ),
                                          ],
                                          2
                                      )
                                    : t._e(),
                            ]),
                        ],
                        1
                    );
                },
                staticRenderFns: [],
            };
        },
        function (t, e, n) {
            function o(t, e) {
                for (var n = 0; n < t.length; n++) {
                    var o = t[n],
                        r = f[o.id];
                    if (r) {
                        r.refs++;
                        for (var i = 0; i < r.parts.length; i++)
                            r.parts[i](o.parts[i]);
                        for (; i < o.parts.length; i++)
                            r.parts.push(u(o.parts[i], e));
                    } else {
                        for (var s = [], i = 0; i < o.parts.length; i++)
                            s.push(u(o.parts[i], e));
                        f[o.id] = { id: o.id, refs: 1, parts: s };
                    }
                }
            }
            function r(t) {
                for (var e = [], n = {}, o = 0; o < t.length; o++) {
                    var r = t[o],
                        i = r[0],
                        s = r[1],
                        a = r[2],
                        u = r[3],
                        l = { css: s, media: a, sourceMap: u };
                    n[i]
                        ? n[i].parts.push(l)
                        : e.push((n[i] = { id: i, parts: [l] }));
                }
                return e;
            }
            function i(t, e) {
                var n = h(),
                    o = g[g.length - 1];
                if ("top" === t.insertAt)
                    o
                        ? o.nextSibling
                            ? n.insertBefore(e, o.nextSibling)
                            : n.appendChild(e)
                        : n.insertBefore(e, n.firstChild),
                        g.push(e);
                else {
                    if ("bottom" !== t.insertAt)
                        throw new Error(
                            "Invalid value for parameter 'insertAt'. Must be 'top' or 'bottom'."
                        );
                    n.appendChild(e);
                }
            }
            function s(t) {
                t.parentNode.removeChild(t);
                var e = g.indexOf(t);
                e >= 0 && g.splice(e, 1);
            }
            function a(t) {
                var e = document.createElement("style");
                return (e.type = "text/css"), i(t, e), e;
            }
            function u(t, e) {
                var n, o, r;
                if (e.singleton) {
                    var i = v++;
                    (n = b || (b = a(e))),
                        (o = l.bind(null, n, i, !1)),
                        (r = l.bind(null, n, i, !0));
                } else
                    (n = a(e)),
                        (o = c.bind(null, n)),
                        (r = function () {
                            s(n);
                        });
                return (
                    o(t),
                    function (e) {
                        if (e) {
                            if (
                                e.css === t.css &&
                                e.media === t.media &&
                                e.sourceMap === t.sourceMap
                            )
                                return;
                            o((t = e));
                        } else r();
                    }
                );
            }
            function l(t, e, n, o) {
                var r = n ? "" : o.css;
                if (t.styleSheet) t.styleSheet.cssText = y(e, r);
                else {
                    var i = document.createTextNode(r),
                        s = t.childNodes;
                    s[e] && t.removeChild(s[e]),
                        s.length ? t.insertBefore(i, s[e]) : t.appendChild(i);
                }
            }
            function c(t, e) {
                var n = e.css,
                    o = e.media,
                    r = e.sourceMap;
                if (
                    (o && t.setAttribute("media", o),
                    r &&
                        ((n += "\n/*# sourceURL=" + r.sources[0] + " */"),
                        (n +=
                            "\n/*# sourceMappingURL=data:application/json;base64," +
                            btoa(
                                unescape(encodeURIComponent(JSON.stringify(r)))
                            ) +
                            " */")),
                    t.styleSheet)
                )
                    t.styleSheet.cssText = n;
                else {
                    for (; t.firstChild; ) t.removeChild(t.firstChild);
                    t.appendChild(document.createTextNode(n));
                }
            }
            var f = {},
                p = function (t) {
                    var e;
                    return function () {
                        return (
                            "undefined" == typeof e &&
                                (e = t.apply(this, arguments)),
                            e
                        );
                    };
                },
                d = p(function () {
                    return /msie [6-9]\b/.test(
                        window.navigator.userAgent.toLowerCase()
                    );
                }),
                h = p(function () {
                    return (
                        document.head ||
                        document.getElementsByTagName("head")[0]
                    );
                }),
                b = null,
                v = 0,
                g = [];
            t.exports = function (t, e) {
                (e = e || {}),
                    "undefined" == typeof e.singleton && (e.singleton = d()),
                    "undefined" == typeof e.insertAt && (e.insertAt = "bottom");
                var n = r(t);
                return (
                    o(n, e),
                    function (t) {
                        for (var i = [], s = 0; s < n.length; s++) {
                            var a = n[s],
                                u = f[a.id];
                            u.refs--, i.push(u);
                        }
                        if (t) {
                            var l = r(t);
                            o(l, e);
                        }
                        for (var s = 0; s < i.length; s++) {
                            var u = i[s];
                            if (0 === u.refs) {
                                for (var c = 0; c < u.parts.length; c++)
                                    u.parts[c]();
                                delete f[u.id];
                            }
                        }
                    }
                );
            };
            var y = (function () {
                var t = [];
                return function (e, n) {
                    return (t[e] = n), t.filter(Boolean).join("\n");
                };
            })();
        },
        function (t, e, n) {
            var o = n(83);
            "string" == typeof o && (o = [[t.id, o, ""]]);
            n(88)(o, {});
            o.locals && (t.exports = o.locals);
        },
    ]);
});
//# sourceMappingURL=vue-select.js.map
