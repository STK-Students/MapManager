## Was ist das?

Alle Einstellungen aus der MapFile Spezifikation werden hier in der gleichen Reihenfolge wie auf
https://mapserver.org/mapfile/ eingetragen.
Alle Werte die *deprecated* sind, werden von vornherein ausgeschlossen und müssen nicht notiert werden.

Falls die Einstellung im MapManger implementiert ist, wird ein X in der Liste gesetzt.
Außerdem muss der Anzeigename im GUI durch ein `/` getrennt daneben geschrieben werden.

Dies dient der Kontrolle, ob alle Features des MapFile abgebildet sind.
**Falls Felder bewusst nicht abgebildet werden, wird es hier dokumentiert.**

## Optionen des LABEL

FONT "arial"
TYPE truetype
SIZE 8
COLOR 0 0 0
OUTLINECOLOR 255 255 255
OUTLINEWIDTH 2
POSITION UR
FORCE true
ANGLE []
PARTIALS
TEXT ("BW " + "[gid]")
MAXSCALEDENOM 5000