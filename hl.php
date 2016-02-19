<?php

class SyntaxHighlight {
  public static function process($s) {
    $s = htmlspecialchars($s);

    $regexp = array(

      // Punctuations
      '#(
        [][!%^*()+|~=`{}:!\'?,./$]+|
        &gt;|&lt;|&amp;
        )#x' => '<span class="P">$1</span>',

      // Comments
      '{(
        /\*.*\*/|
        \#.*|
        &lt;!--.*--&gt;
        )}x' => '<span class="C">$1</span>',

      // Strings
      '/(
        &quot;.*?(?<!\\\\)(\\\\\\\\)*&quot;|
        (?<!http:)(?<!https:)\/\/.*|
        \'.*?(?<!\\\\)(\\\\\\\\)*\'
        )/x' => '<span class="S">$1</span>',

      // Numbers (also look for Hex)
      '/(?<!\w)(
        (0x|\#)[[:xdigit:]]+|
        \d+|
        \d+(px|em|cm|mm|rem|s|\%)
      )(?!\w)/ix'
      => '<span class="N">$1</span>',

      // Make the bold assumption that an
      // all uppercase word has a special meaning
      '/(?<!\w|>|\#|&quot;)(
        [[:upper:]_0-9]{2,}
      )(?!\w)/x'
      => '<span class="D">$1</span>',

      // Keywords
      '/(?<!\w|\$|%|@|>)(
        and|or|xor|for|do|while|foreach|as|return|die|exit|if|then|else|
        elseif|new|delete|try|throw|catch|finally|class|function|string|
        array|object|resource|var|bool|boolean|int|integer|float|double|
        real|string|array|global|const|static|public|private|protected|
        published|extends|switch|true|false|null|void|this|self|struct|
        char|signed|unsigned|short|long|fi|esac|done|goto
      )(?!\w|=")/ix'
      => '<span class="K">$1</span>',

      // PHP/Perl-Style Vars: $var, %var, @var
      '/(?<!\w)(
        (\$|%|@)(-&gt;|\w)+
      )(?!\w)/ix'
      => '<span class="V">$1</span>'

    );

    $s = preg_replace(array_keys($regexp), array_values($regexp), $s);

    $s = str_replace("\t", '    ', $s);

    return $s;
  }
}

?>
