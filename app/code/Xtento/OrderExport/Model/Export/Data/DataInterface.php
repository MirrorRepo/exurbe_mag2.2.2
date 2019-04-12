<?php

/**
 * Product:       Xtento_OrderExport
 * ID:            /wnS7JP8C2nvb1jEK6bWS5TtKkvrPoanLM2NpRZ9JJU=
 * Last Modified: 2015-09-01T18:15:56+00:00
 * File:          app/code/Xtento/OrderExport/Model/Export/Data/DataInterface.php
 * Copyright:     Copyright (c) XTENTO GmbH & Co. KG <info@xtento.com> / All rights reserved.
 */

namespace Xtento\OrderExport\Model\Export\Data;

interface DataInterface {
    public function getExportData($entityType, $collectionItem);
    public function getConfiguration();
}