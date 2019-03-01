<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */

declare(strict_types=1);

namespace LazyProperty\Exception;

use InvalidArgumentException;
use function get_class;
use function gettype;
use function is_object;
use function spl_object_hash;
use function sprintf;

/**
 * Exception for invalid context access for lazy properties
 */
class InvalidAccessException extends InvalidArgumentException implements ExceptionInterface
{
    /**
     * @param mixed $caller
     */
    public static function invalidContext($caller, object $instance, string $property) : self
    {
        return new self(sprintf(
            'The requested lazy property "%s" of "%s#%s" is not accessible from the context of in "%s"',
            $property,
            get_class($instance),
            spl_object_hash($instance),
            is_object($caller) ? get_class($caller) : gettype($caller)
        ));
    }
}
