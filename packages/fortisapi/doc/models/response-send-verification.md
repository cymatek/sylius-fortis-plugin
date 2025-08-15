
# Response Send Verification

## Structure

`ResponseSendVerification`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type135Enum)`](../../doc/models/type-135-enum.md) | Optional | Resource Type<br><br>**Default**: `Type135Enum::SENDVERIFICATION` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data32`](../../doc/models/data-32.md) | Optional | - | getData(): ?Data32 | setData(?Data32 data): void |

## Example (as JSON)

```json
{
  "type": "SendVerification",
  "data": {
    "id": "id0",
    "user_id": "user_id8",
    "hash": "hash6",
    "created_ts": 114
  }
}
```

