
# Response Transaction Bin Info

## Structure

`ResponseTransactionBinInfo`

## Fields

| Name | Type | Tags | Description | Getter | Setter |
|  --- | --- | --- | --- | --- | --- |
| `type` | [`?string(Type113Enum)`](../../doc/models/type-113-enum.md) | Optional | Resource Type<br><br>**Default**: `Type113Enum::TRANSACTIONBININFO` | getType(): ?string | setType(?string type): void |
| `data` | [`?Data28`](../../doc/models/data-28.md) | Optional | - | getData(): ?Data28 | setData(?Data28 data): void |

## Example (as JSON)

```json
{
  "type": "TransactionBinInfo",
  "data": {
    "issuer_bank_name": "issuer_bank_name6",
    "country_code": "country_code0",
    "detail_card_product": "detail_card_product2",
    "detail_card_indicator": "detail_card_indicator2",
    "fsa_indicator": "fsa_indicator8"
  }
}
```

