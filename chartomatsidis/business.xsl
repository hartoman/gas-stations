<?xml version="1.0" encoding="UTF-8"?>

<!-- stylesheet start -->
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- type of xml element to match -->
<xsl:template match="business">
  <html>
  <head>
      <!-- apply css style to all elements -->
    <style>
    * {
        text-align: center;
        justify-content: center;
        padding: 5px;
        margin: auto;
    }
    </style>
  </head>

  <body>
    <h1> <xsl:value-of select="bizname" /> </h1>
    
    <!-- general business data table start -->
    <table border="1">
    <tr>
    <th style="padding:12px;">ΑΦΜ </th>
    <td><xsl:value-of select="afm" /></td>
    </tr>
    <tr>
    <th style="padding:12px;">Νομός </th>
    <td><xsl:value-of select="county" /></td>
    </tr>
    <tr>
    <th style="padding:12px;">Δήμος </th>
    <td><xsl:value-of select="municipal" /></td>
    </tr>
    <tr>
    <th style="padding:12px;">Διεύθυνση </th>
    <td><xsl:value-of select="address" /></td>
    </tr>
    </table>
    <!-- general business data table end  -->


    <!-- pricing stats table start -->
    <table border="1">

    <!-- column headers start -->
    <tr bgcolor="#00ced1" >
    <th style="padding:10px 100px 10px 100px;">Κάυσιμο </th>
    <th style="padding:12px;">Μέγιστη </th>
    <th style="padding:12px;">Ελάχιστη </th>
    <th style="padding:12px;">Αριθμός Προσφορών </th>
    </tr>
    <!-- column headers start-->

    <!-- 1st row start -->
     <tr>
    <th>Αμόλυβδη 95</th>
    <!-- select only the offers where fueltype is unleaded-->
<xsl:for-each select="offers/offer[fueltype='Αμόλυβδη 95']">
    <!-- sort them on basis of price DESCENDING-->
    <xsl:sort select="price" data-type="number" order="descending"/>
    <!-- max is the first element of the list-->
    <xsl:if test="position() = 1">
       <td><xsl:value-of select="price"/></td>
    </xsl:if>
    <!-- min is the last element of the list-->
    <xsl:if test="position() = last()">
      <td><xsl:value-of select="price"/></td>
    </xsl:if>
</xsl:for-each>
    <!-- total number of relevant offers is given by count-->
    <td><xsl:value-of select="count(offers/offer[fueltype='Αμόλυβδη 95'])" /></td>
    </tr>
    <!-- 1st row end -->

    <!-- 2nd row start -->
     <tr>
    <th>Πετρέλαιο Κίνησης </th>
<xsl:for-each select="offers/offer[fueltype='Πετρέλαιο Κίνησης']">
    <xsl:sort select="price" data-type="number" order="descending"/>
    <xsl:if test="position() = 1">
       <td><xsl:value-of select="price"/></td>
    </xsl:if>
    <xsl:if test="position() = last()">
      <td><xsl:value-of select="price"/></td>
    </xsl:if>
</xsl:for-each>
<td><xsl:value-of select="count(offers/offer[fueltype='Πετρέλαιο Κίνησης'])" /></td>
    </tr>
    <!--  2nd row end -->

    <!-- 3rd row start-->
     <tr>
    <th>Πετρέλαιο Θέρμανσης </th>
<xsl:for-each select="offers/offer[fueltype='Πετρέλαιο Θέρμανσης']">
    <xsl:sort select="price" data-type="number" order="descending"/>
    <xsl:if test="position() = 1">
       <td><xsl:value-of select="price"/></td>
    </xsl:if>
    <xsl:if test="position() = last()">
      <td><xsl:value-of select="price"/></td>
    </xsl:if>
</xsl:for-each>
<td><xsl:value-of select="count(offers/offer[fueltype='Πετρέλαιο Θέρμανσης'])" /></td>
    </tr>
     <!-- 3rd row end -->
    </table>
    <!-- pricing stats table end -->

    <!-- total number of offers is given by count-->
    <h2>Συνολικές προσφορές: <xsl:value-of select="count(offers/offer)" /> </h2>
    
    <!-- offer stats table start -->
<table border="1" style="border-collapse:collapse;">

    <!-- column headers start -->
    <tr bgcolor="#00ced1" >
    <th style="padding:0px 50px 0px 50px;">Κάυσιμο </th>
    <th style="padding:0px 30px 0px 30px;">Τιμή </th>
    <th style="padding:0px 30px 0px 30px;">Ημ/νια λήξης </th>
    <th style="padding:0px 30px 0px 30px;">Σε ισχύ? </th>
    </tr>
    <!-- column headers end -->

    <!-- iteration through all offers start -->
    <xsl:for-each select="offers/offer">
    <!-- sort by fueltype -->
    <xsl:sort select="fueltype"/>
    <tr>
    <h1></h1>
    <td><xsl:value-of select="fueltype"/> </td>
    <td><xsl:value-of select="price"/> </td>
    <td><xsl:value-of select="expdate"/> </td>
    <td><xsl:value-of select="isactive"/> </td>
    </tr>
    </xsl:for-each>
    <!-- iteration through all offers end -->

    </table>
    <!-- offer stats table end -->

  </body>
  </html>
</xsl:template>

</xsl:stylesheet>
    <!-- stylesheet end -->