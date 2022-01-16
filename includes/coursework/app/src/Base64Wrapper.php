<?php

/**
 * Class Base64Wrapper for the Base 64 encoding/decoding library
 *
 * Methods available are: Encode/Decode the given string with base 64 encoding
 *
 * @author Jakub Chamera
 * @package Coursework
 */

namespace Coursework;

class Base64Wrapper
{

    /**
     * Function for encoding data in base64 format.
     * @param $string_to_encode -The string value to be encoded.
     * @return false|string Returns the encoded string on success and false on failure.
     */
  public function encode_base64($string_to_encode)
  {
    $encoded_string = false;
    if (!empty($string_to_encode))
    {
      $encoded_string = base64_encode($string_to_encode);
    }
    return $encoded_string;
  }

    /**
     * Function for decoding previously encoded base64 data.
     * @param $string_to_decode -The encoded string to be decoded.
     * @return false|string Returns the decoded string on success and false on failure.
     */
  public function decode_base64($string_to_decode)
  {
    $decoded_string = false;
    if (!empty($string_to_decode))
    {
      $decoded_string = base64_decode($string_to_decode);
    }
    return $decoded_string;
  }
}

