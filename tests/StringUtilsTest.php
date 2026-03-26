<?php

namespace Mrwang211\CcCedictPhp\Tests;

use Mrwang211\CcCedictPhp\StringUtils;

describe('StringUtils', function () {
    describe('between', function () {
        it('should return null when from or to is not present in the passed in string', function () {
            expect(StringUtils::between('hello [world some filler text.', '[', ']'))
                ->and(StringUtils::between('hello world] some filler text.', '[', ']'))
                ->toBeNull();
        });

        it('works with advanced example', function () {
            expect(StringUtils::between('你 你 [ni3] /you (informal, as opposed to courteous 您[nin2])/', '/', '/')[0])->toBe('you (informal, as opposed to courteous 您[nin2])');
        });

        it('works with different from and to', function () {
            expect(StringUtils::between('hello [world] some filler text.', '[', ']'))->toEqual(['world', 6, 12]);
        });

        it('works with same from and to', function () {
            expect(StringUtils::between('hello /world/ some crazy text.', '/', '/'))->toEqual(['world', 6, 12]);
        });
    });
});
