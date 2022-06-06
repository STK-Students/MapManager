## Was ist das?

Alle Einstellungen aus der MapFile Spezifikation werden hier in der gleichen Reihenfolge wie auf
https://mapserver.org/mapfile/ eingetragen.
Alle Werte die *deprecated* sind, werden von vornherein ausgeschlossen und müssen nicht notiert werden.

Falls die Einstellung im MapManger implementiert ist, wird ein X in der Liste gesetzt.
Außerdem muss der Anzeigename im GUI durch ein `/` getrennt daneben geschrieben werden.

Dies dient der Kontrolle, ob alle Features des MapFile abgebildet sind.
**Falls Felder bewusst nicht abgebildet werden, wird es hier dokumentiert.**

## Optionen der MAP

- [X] ANGLE / Kartendrehung
- [ ] CONFIG -> Viele geschachtelte Settings, eventuell generelle Dienst-Settings oder Out-of-Scope
- [ ] DEBUG -> Out of Scope
- [ ] DEFRESOLUTION
- [ ] EXTENT
- [ ] FONTSET
- [ ] IMAGECOLOR
- [ ] IMAGETYPE
- [ ] [LAYER](Nested)
- [ ] [LEGEND](Nested)
- [X] MAXSIZE / Maximale Auflösung
- [X] NAME
- [ ] [OUTPUTFORMAT](Nested)
- [ ] [PROJECTION](Nested)
- [ ] [QUERYMAP](Nested)
- [ ] [REFERENCE](Nested)
- [ ] RESOLUTION
- [X] SCALEDENOM / Maßstab
- [ ] SCALEBAR
- [ ] SHAPEPATH
- [X] SIZE / Auflösung -> Split into two inputs (x & y)
- [ ] STATUS -> Eventuell nicht im normalen Layout enthalten?
- [ ] SYMBOLSET -> Immer gleich?
- [ ] [SYMBOL](nested)
- [X] UNITS / Koordinateneinheit
- [ ] [WEB](nested) 

