<?php
    namespace Weasy\Utils;

    enum DbExecutionTypes {
        case Insert;
        case Delete;
        case SELECT_BY_PROP;
        case SELECT_ALL;
    }
