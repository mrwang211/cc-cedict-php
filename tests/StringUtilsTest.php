<?php

namespace Mrwang211\CcCedictPhp\Tests;

use Mrwang211\CcCedictPhp\StringUtils;

describe('StringUtils', function () {
    describe('between', function () {
        it('should return null when to appears before from', function () {
            expect(StringUtils::between(']hello world[', '[', ']'))->toBeNull();
        });

        it('should return null when content between from and to is empty', function () {
            expect(StringUtils::between('some content here[] wei rd stuff', '[', ']'))
                ->toBeNull()
                ->and(StringUtils::between('[   ]', '[', ']'))
                ->not()
                ->toBeNull();
        });

        it('should return null when from or to is not present in the passed in string', function () {
            expect(StringUtils::between('hello [world some filler text.', '[', ']'))
                ->and(StringUtils::between('hello world] some filler text.', '[', ']'))
                ->toBeNull();
        });

        it('works with advanced example', function () {
            expect(StringUtils::between('你 你 [ni3] /you (informal, as opposed to courteous 您[nin2])/', '/', '/'))->toBe('you (informal, as opposed to courteous 您[nin2])');
        });

        it('works with different from and to', function () {
            expect(StringUtils::between('hello [world] some filler text.', '[', ']'))->toBe('world');
        });

        it('works with same from and to', function () {
            expect(StringUtils::between('hello /world/ some crazy text.', '/', '/'))->toBe('world');
        });
    });

    describe('betweenFirstAndLast', function () {
        it('should return null when to appears before from', function () {
            expect(StringUtils::between(']hello world[', '[', ']'))->toBeNull();
        });

        it('should return null when content between from and to is empty', function () {
            expect(StringUtils::between('some content here[] wei rd stuff', '[', ']'))
                ->toBeNull()
                ->and(StringUtils::between('[   ]', '[', ']'))
                ->not()
                ->toBeNull();
        });

        it('should handle with multiple from and to', function () {
            expect(StringUtils::betweenFirstAndLast('[hello [world] some filler text. [[][[][][][]]] all this should be captured]', '[', ']'))
                ->toBe('hello [world] some filler text. [[][[][][][]]] all this should be captured');
        });
    });

});
