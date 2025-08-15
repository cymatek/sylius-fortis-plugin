
# Response Token

## Structure

`ResponseToken`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type92Enum)`](../../doc/models/type-92-enum.md) | Optional | Resource Type<br><br>**Default**: `Type92Enum::TOKEN` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data25`](../../doc/models/data-25.md) | Optional | - | getData(): ?Data25 | setData(?Data25 data): void |

## Example (as JSON)

```json
{
  "type": "Token",
  "data": {
    "account_holder_name": "account_holder_name0",
    "account_vault_api_id": "account_vault_api_id4",
    "token_api_id": "token_api_id6",
    "accountvault_c1": "accountvault_c14",
    "accountvault_c2": "accountvault_c28"
  }
}
```

