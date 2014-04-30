<?php
/* ============================================================================
(c) Copyright 2012-2014 Hewlett-Packard Development Company, L.P.

     Licensed under the Apache License, Version 2.0 (the "License");
     you may not use this file except in compliance with the License.
     You may obtain a copy of the License at

             http://www.apache.org/licenses/LICENSE-2.0

     Unless required by applicable law or agreed to in writing, software
     distributed under the License is distributed on an "AS IS" BASIS,
     WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
     See the License for the specific language governing permissions and
     limitations under the License.
============================================================================ */

namespace OpenStack\Common\Transport\Exception;

/**
 * Exception that represents a 500 Internal Server Error.
 *
 * This class is thrown when a server encounters an unexpected condition which
 * prevents it from fulfilling the request. Sometimes this error is used as a
 * generic catch-all by an OpenStack API.
 */
class ServerException extends RequestException
{
}