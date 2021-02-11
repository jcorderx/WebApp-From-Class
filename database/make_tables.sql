-- Create the Admins table.
CREATE TABLE Admins (
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    fName VARCHAR(255) NOT NULL,
    lName VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    phoneNumber CHAR(10) NOT NULL,
    PRIMARY KEY(id)
);

-- Create sponsors table.
CREATE TABLE Sponsors (
    id INT NOT NULL AUTO_INCREMENT,
    companyName VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    phoneNumber CHAR(10) NOT NULL,
    PRIMARY KEY(id)
);

-- Create drivers table.
CREATE TABLE Drivers (
    id INT NOT NULL AUTO_INCREMENT,
    fName VARCHAR(255) NOT NULL,
    lName VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phoneNumber CHAR(10) NOT NULL,
    houseNumber INT NOT NULL,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    stateCode CHAR(2) NOT NULL,
    zipcode CHAR(5) NOT NULL,
    isActive INT NOT NULL DEFAULT 0,
    PRIMARY KEY(id)
);

-- A list of applications sent to sponsors from drivers.
CREATE TABLE DriverApplications (
    driverId INT NOT NULL,
    sponsorId INT NOT NULL,
    PRIMARY KEY (driverId, SponsorId),
    FOREIGN KEY(driverId) REFERENCES Drivers(id),
    FOREIGN KEY(sponsorId) REFERENCES Sponsors(id)
);

-- This table stores the relationship between a driver and a sponsor,
-- i.e. the credits driver X has under sponsor Y.
CREATE TABLE DriverSponsorRelations (
    sponsorId INT NOT NULL,
    driverId INT NOT NULL,
    credits INT NOT NULL DEFAULT 0,
    pRatio DOUBLE NOT NULL DEFAULT 0.01,
    PRIMARY KEY(sponsorId, driverId),
    FOREIGN KEY(sponsorId) REFERENCES Sponsors(id),
    FOREIGN KEY(driverId) REFERENCES Drivers(id)
);

-- A catalog. We'll have to fill this in with rules for selecting products
-- once we flesh out the implementation of that stuff.
CREATE TABLE Catalogs (
    id INT NOT NULL AUTO_INCREMENT,
    sponsorId INT NOT NULL,
    selectionMode VARCHAR(255) NOT NULL DEFAULT 'CATEGORY',
    CHECK (selectionMode='LIST' OR selectionMode='CATEGORY' OR selectionMode='RULES'),
    PRIMARY KEY(id),
    FOREIGN KEY(sponsorId) REFERENCES Sponsors(id)
);

CREATE TABLE Category (
    id INT NOT NULL, -- ebay category id
    categoryParentId INT NOT NULL DEFAULT -1, -- ebay category id. -1 means it's a top-level category
    categoryName VARCHAR(255) NOT NULL,
    leafCategory INT NOT NULL DEFAULT 0,
    PRIMARY KEY(id)
);

-- A list of categories that a catalog can pull from.
CREATE TABLE CatalogCategories (
    catalogId INT NOT NULL,
    categoryId INT NOT NULL,
    PRIMARY KEY(catalogId, categoryId),
    FOREIGN KEY(catalogId) REFERENCES Catalogs(id),
    FOREIGN KEY(categoryId) REFERENCES Category(id)
);

CREATE TABLE CatalogItemFilters (
    catalogId INT NOT NULL,
    itemFilter VARCHAR(255) NOT NULL,
    itemFilterValue VARCHAR(255) NOT NULL,
    PRIMARY KEY(catalogId, itemFilter, itemFilterValue),
    FOREIGN KEY(catalogId) REFERENCES Catalogs(id)
);

CREATE TABLE CatalogItemKeywords (
    catalogId INT NOT NULL,
    keywords VARCHAR(255) NOT NULL,
    PRIMARY KEY(catalogId, keywords),
    FOREIGN KEY(catalogId) REFERENCES Catalogs(id)
);


-- An item from eBay. May or may not be in any given catalog.
-- It is created as a separate table so as to maintain purchase history
-- for drivers.
-- If the item isn't in any of the catalogs, then it shouldn't be purchasable,
-- although it may still be active as a pending request from a driver, an
-- item that has been shipped to a driver, or an item in a driver's purchase
-- history.
-- This implies that once an item is added to this table, it is never removed,
-- even if it is not purchasable anymore.

CREATE TABLE CatalogItems (
    id BIGINT NOT NULL, -- the ebay item id
    title VARCHAR(500) NOT NULL, -- name of the item
    location VARCHAR(255), -- location the item is being shipped from i.e. "Portland,OR,USA"
    viewItemURL VARCHAR(500) NOT NULL, -- url of the item on ebay
    imageURL VARCHAR(500), -- image url of the item, if available
    shippingCost DECIMAL(10, 2) NOT NULL,
    currentPrice DECIMAL(10, 2) NOT NULL,
    conditionDisplayName VARCHAR(50), -- the condition it's in, i.e. "New"
    buyItNowAvailable INT NOT NULL DEFAULT 0, -- 1 if the item is available, 0 otherwise
    startTime DATETIME NOT NULL,
    endTime DATETIME NOT NULL,
    categoryId INT NOT NULL,
    categoryName VARCHAR(50) NOT NULL,
    PRIMARY KEY(id)
);

-- A review for a catalog item.
CREATE TABLE CatalogItemReviews (
    id INT NOT NULL AUTO_INCREMENT,
    itemId BIGINT NOT NULL,
    driverId INT NOT NULL, -- the driver who left the review
    rating FLOAT NOT NULL,
    description VARCHAR(8000),
    CHECK (rating >= 0 AND rating <= 5),
    FOREIGN KEY(itemId) REFERENCES CatalogItems(id),
    FOREIGN KEY(driverId) REFERENCES Drivers(id),
    PRIMARY KEY(id)
);

-- A list of items belonging to the catalog of a particular sponsor.
CREATE TABLE CatalogList (
    catalogId INT NOT NULL,
    itemId BIGINT NOT NULL,
    FOREIGN KEY(catalogId) REFERENCES Catalogs(id),
    FOREIGN KEY(itemId) REFERENCES CatalogItems(id),
    PRIMARY KEY(catalogId, itemId)
);

-- A Driver's purchase history.
CREATE TABLE PurchaseHistory (
    driverId INT NOT NULL,
    itemId BIGINT NOT NULL,
    sponsorId INT NOT NULL,
    purchaseTime TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    price INT NOT NULL,
    FOREIGN KEY(driverId) REFERENCES Drivers(id),
    FOREIGN KEY(itemId) REFERENCES CatalogItems(id),
    PRIMARY KEY(driverId, itemId, purchaseTime)
);

-- All of a driver's orders. This includes:
--  1. Requested orders
--  2. Shipped orders
--  3. Arrived orders

-- Also includes the sponsor who they are requesting it from.
CREATE TABLE DriverOrders (
    driverId INT NOT NULL,
    itemId BIGINT NOT NULL,
    sponsorId INT NOT NULL,
    orderStatus VARCHAR(7) NOT NULL DEFAULT 'REQUEST',
    CHECK (orderStatus='REQUEST' OR orderStatus='SHIPPED' OR orderStatus='ARRIVED'),
    FOREIGN KEY(driverId) REFERENCES Drivers(id),
    FOREIGN KEY(itemId) REFERENCES CatalogItems(id),
    FOREIGN KEY(sponsorId) REFERENCES Sponsors(id),
    PRIMARY KEY(driverId, itemId)
);

/* A driver's current purchase cart. */
CREATE TABLE cart (
    did INT, /* driver id */
    sid INT, /* sponsor id */
    cid BIGINT, /* catalog item id */

    FOREIGN KEY(did) REFERENCES Drivers(id),
    FOREIGN KEY(sid) REFERENCES Sponsors(id),
    FOREIGN KEY(cid) REFERENCES CatalogItems(id),
    PRIMARY KEY(did,sid,cid)
);

CREATE TABLE PointHistory (
    driverId INT NOT NULL,
    sponsorId INT NOT NULL,
    changeAm INT, /* amount changed */
    dateC DATE, /* date the change took place */
    FOREIGN KEY(driverId) REFERENCES Drivers(id),
    FOREIGN KEY(sponsorId) REFERENCES Sponsors(id)
);

CREATE TABLE AutomaticAlerts (
    id INT NOT NULL AUTO_INCREMENT,
    alertText VARCHAR(2048) NOT NULL,
    targetAccType VARCHAR(7) NOT NULL,
    dateSent TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    driverId INT,
    sponsorId INT,
    adminId INT,
    hasBeenSeen INT DEFAULT 0,
    CHECK (targetAccType="SPONSOR" or targetAccType="DRIVER" or targetAccType="ADMIN"),
    PRIMARY KEY(id),
    FOREIGN KEY(driverId) REFERENCES Drivers(id),
    FOREIGN KEY(sponsorId) REFERENCES Sponsors(id),
    FOREIGN KEY(adminId) REFERENCES Admins(id)
);