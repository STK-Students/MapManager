### MapManager - WebApp for easy creation of UMN MapServer Mapfiles

Contact `lars.baum@stadt-koeln.de` in case of questions.

All paths in the config are relative to the location of the project root folder.

#### How do add new attributes:
- Add to form while respecting naming conventions (check formSubmitter docs).
- Make sure they are picked up by the formSubmitter (write specialCaseHandlers if necessary).
- Make sure they are added as allowed arguments in the corresponding Deserializer.
  - Make sure they are handled correctly if it's a special case (new switch branch with custom logic).
- Make sure they are also added to the Serializer in case if it's a special case.

#### How to add new subtypes:
- Add new Serializers and Deserializers for the new subtype.
- Make sure the corresponding Writers and Parsers of the MapFileParser library have the right imports set. 
  The MapFile will be empty when this is not the case.