/* To The North — labelled placeholder image slots.
   Generates a data-URI SVG: TTN slate fill, TTN grey Inter labels,
   so the prototype states what photograph each slot is waiting for.
   window.TTN_SLOT(w, h, lines, fs) -> data URI usable as an <img src>.
   Draw the SVG near its rendered size so labels land around 13px. */
(function () {
  function esc(s) {
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
  }
  window.TTN_SLOT = function (w, h, lines, fs) {
    fs = fs || 28;
    var lh = Math.round(fs * 1.7);
    var n = lines.length;
    var texts = '';
    for (var i = 0; i < n; i++) {
      var y = h / 2 + (i - (n - 1) / 2) * lh;
      var first = i === 0;
      texts +=
        "<text x='" + w / 2 + "' y='" + y + "' text-anchor='middle' dominant-baseline='middle'" +
        " font-family='Inter, sans-serif' font-size='" + fs + "'" +
        (first ? " font-weight='600' letter-spacing='" + fs * 0.08 + "'" : " font-weight='400'") +
        " fill='#8A8A8A'>" + esc(first ? String(lines[i]).toUpperCase() : lines[i]) + '</text>';
    }
    var svg =
      "<svg xmlns='http://www.w3.org/2000/svg' width='" + w + "' height='" + h + "' viewBox='0 0 " + w + ' ' + h + "'>" +
      "<rect width='" + w + "' height='" + h + "' fill='#1E1E1E'/>" +
      "<rect x='1' y='1' width='" + (w - 2) + "' height='" + (h - 2) + "' fill='none' stroke='#333333' stroke-dasharray='6 6'/>" +
      texts + '</svg>';
    return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
  };
})();
