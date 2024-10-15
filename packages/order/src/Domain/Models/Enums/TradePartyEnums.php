<?php

namespace RedJasmine\Order\Domain\Models\Enums;

use RedJasmine\Support\Helpers\Enums\EnumsHelper;

enum TradePartyEnums: string
{

    use EnumsHelper;

    case PLATFORM = 'platform';

    case SELLER = 'seller';

    case BUYER = 'buyer';

    case SUPPLIER = 'supplier';

    case STORE = 'store';

    case GUIDE = 'guide';

    case CHANNEL = 'channel';

}
