
# Response User Api Key

## Structure

`ResponseUserApiKey`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type128Enum)`](../../doc/models/type-128-enum.md) | Optional | Resource Type<br><br>**Default**: `Type128Enum::USERAPIKEY` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data33`](../../doc/models/data-33.md) | Optional | - | getData(): ?Data33 | setData(?Data33 data): void |

## Example (as JSON)

```json
{
  "type": "UserApiKey",
  "data": {
    "user_api_key": "user_api_key2"
  }
}
```

