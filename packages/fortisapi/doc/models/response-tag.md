
# Response Tag

## Structure

`ResponseTag`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type77Enum)`](../../doc/models/type-77-enum.md) | Optional | Resource Type<br><br>**Default**: `Type77Enum::TAG` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data22`](../../doc/models/data-22.md) | Optional | - | getData(): ?Data22 | setData(?Data22 data): void |

## Example (as JSON)

```json
{
  "type": "Tag",
  "data": {
    "location_id": "location_id4",
    "title": "title6",
    "id": "id0",
    "created_ts": 114,
    "modified_ts": 190
  }
}
```

