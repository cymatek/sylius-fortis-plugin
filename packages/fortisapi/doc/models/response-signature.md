
# Response Signature

## Structure

`ResponseSignature`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type72Enum)`](../../doc/models/type-72-enum.md) | Optional | Resource Type<br><br>**Default**: `Type72Enum::SIGNATURE` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data21`](../../doc/models/data-21.md) | Optional | - | getData(): ?Data21 | setData(?Data21 data): void |

## Example (as JSON)

```json
{
  "type": "Signature",
  "data": {
    "signature": "signature8",
    "resource": "AccountVault",
    "resource_id": "resource_id6",
    "id": "id0",
    "created_ts": 114
  }
}
```

